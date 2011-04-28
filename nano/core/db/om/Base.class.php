<?php
namespace nano\core\db\om;


/**
 * Base class 
 * 
 * Base
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.db.om
 */
class Base {
	
	protected $dbConfig = 'default';
	protected $modelName = null;
	protected $dbName = null;
	protected $fields = array();
	protected $newFieldNameMap = array();
	protected $data = array();
	protected $lastInsertId = null;
	
	
	protected $primaryKey = array();
	protected $tableName = null;
	
	protected $referencedByCacheKeys = array();
	
	/**
	 * Function - Add Cache Reference Key
	 * @param mixed $newCacheKey New Cache Key
	 */
	public function addCacheReferenceKey($newCacheKey){
		if(!in_array($newCacheKey,$this->referencedByCacheKeys)){
			$this->referencedByCacheKeys[] = $newCacheKey;
			$memcached = \nano\core\cache\Memcached::getInstance($this->dbConfig);
			$existingReferenceByCacheKeys = unserialize($memcached->get($this->getCacheReferenceKeyIdentifier()));
			if(is_array($existingReferenceByCacheKeys)){
				$this->referencedByCacheKeys = array_unique(array_merge($this->referencedByCacheKeys,$existingReferenceByCacheKeys));
				$memcached->set($this->getCacheReferenceKeyIdentifier(),serialize($this->referencedByCacheKeys));
			} else {
				$memcached->set($this->getCacheReferenceKeyIdentifier(),serialize($this->referencedByCacheKeys));
			}
		}
	}
	
	/**
	 * Function - Get Cache Reference Key Identifier
	 */
	protected function getCacheReferenceKeyIdentifier(){
		$cacheReferenceKeyIdentifier = 'model-cache-ref-'.$this->dbName.'-'.$this->tableName;
		foreach($this->primaryKey as $key){
			$cacheReferenceKeyIdentifier .= '-'.$key.'-'.sha1($this->getValueFromKey($key));
		}
		return $cacheReferenceKeyIdentifier;
	}
	
	/**
	 * Function - Get Cache Reference Keys
	 * @return mixed
	 */
	public function getCacheReferenceKeys(){
		$memcached = \nano\core\cache\Memcached::getInstance($this->dbConfig);
		$existingReferenceByCacheKeys = unserialize($memcached->get($this->getCacheReferenceKeyIdentifier()));
		if(is_array($existingReferenceByCacheKeys)){
			return $existingReferenceByCacheKeys;
		}
		return array();
	}
	
	/**
	 * Function - Flush Cache Against Cache Reference Keys
	 */
	private function flushCacheAgainstCacheReferenceKeys(){
		$this->referencedByCacheKeys = $this->getCacheReferenceKeys();
		foreach($this->referencedByCacheKeys as $referencedByCacheKey){
			if(!$this->doCacheDelete($referencedByCacheKey)){
				throw new \nano\core\exception\CoreException('Failed to delete value from memcached key: '.$referencedByCacheKey);
			}
		}
	}
	
	/**
	 * Function - Do Cache Delete
	 * @param mixed $key Key
	 * @param mixed $attempts Attempts
	 * @return mixed
	 */
	private function doCacheDelete($key,$attempts=5){
		$memcached = \nano\core\cache\Memcached::getInstance($this->dbConfig);
		for($i=0;$i<$attempts;$i++){
			if($memcached->delete($key)){
				return true;
			} elseif ($memcached->getResultCode()==$memcached->getValueFromMemcacheConstant('RES_NOTFOUND')) {
				//if key not found, result therefore can't exist and assume expired, previously deleted or never existed...
				return true;
			}
			//back off against attempts - 1000000 = 1 second.
			usleep(100000 * pow(3,$i));
		}
		return false;
	}
	
	
	/**
	 * Function - __set
	 * @param mixed $key Key
	 * @param mixed $value Value
	 */
	public function __set($key,$value) {
		$this->data[$key] = $value;
	}
	
	
	/**
	 * Function - __get
	 * @param mixed $key Key
	 * @return mixed
	 */
	public function __get($key) {
		if(!isset($this->data[$key])){
			return null;
		}
		$fieldInfo = $this->fields[$this->newFieldNameMap[$key]];
		if($fieldInfo['is_foreign_reference']){
			return \nano\core\db\ORM::getInstance()->getTable($fieldInfo['use_model'],$fieldInfo['use_database'])->retrieveByPk($this->data[$key]);
		}
		return $this->data[$key];
	}
	
	/**
	 * Function - Get Field Name From Model Key
	 * @param mixed $key Key
	 */
	private function getFieldNameFromModelKey($key){
		$fieldName = $key;
		if(isset($this->fields[$fieldName])){
			if($this->fields[$fieldName]['is_foreign_reference']){
				$fieldName = $this->fields[$fieldName]['use_model'];
			}
		}
		return $fieldName;
	}
	
	
	/**
	 * Function - Get Value From Key
	 * @param mixed $key Key
	 * @return mixed
	 */
	public function getValueFromKey($key) {
		if(($key=='')||(!isset($this->data[$key]))) {
			return null;
		}
		return $this->data[$key];
	}
	
	
	/**
	 * Function - Check My Sql Special Function
	 * @param mixed $value Value
	 */
	public function checkMySqlSpecialFunction($value) {
		if(preg_match('/^MYSQL_([\w\d\_\-]+)\((.*?)\)$/',$value,$matches)){
			switch($matches[1]){
				case 'NOW':
					$value = date('Y-m-d H:i:s');
					//return 'NOW()';
					break;
				case 'INET_ATON':
					$value = ip2long($matches[2]);
					//return 'INET_ATON(\''.$matches[2].'\')';
					break;
			}
		}
		
		return addslashes($value);
	}
	
	/**
	 * Function - Save
	 */
	public function save() {
		$db = \nano\core\db\core\Database::open($this->dbConfig);
		$db->setDefaultDatabase($this->dbName);
		if($db->isOpen()) {
			//on save flush cache keys
			if(\nano\core\config\Config::get('caching')==true){
				$this->flushCacheAgainstCacheReferenceKeys();
			}
			return $this->insertUpdate($db);
		} else {
			throw new \nano\core\exception\DatabaseException('Failed to open a connection to the database...');
		}
		return false;
	}
	
	/**
	 * Function - Insert Update
	 * @param mixed $db Db
	 */
	private function insertUpdate($db) {
		$isUpdateable = false;
		$createQuery = 'INSERT INTO `'.$this->tableName.'` (';
		foreach($this->data as $key => $value){
			if(!in_array($key,$this->primaryKey)){
				$isUpdateable = true;
			}
			if(isset($this->newFieldNameMap[$key])){
				$createQuery .= '`'.$this->newFieldNameMap[$key].'`,';
			}
		}
		$createQuery = substr($createQuery,0,-1).') VALUES (';
		foreach($this->data as $key => $value){
			if(isset($this->newFieldNameMap[$key])){
				$createQuery .= '"'.$this->checkMySqlSpecialFunction($value).'",';
			}
		}
		$createQuery = substr($createQuery,0,-1).')';
		if($isUpdateable){
			$createQuery .= ' ON DUPLICATE KEY UPDATE ';
			foreach($this->data as $key => $value){
				if((!in_array($key,$this->primaryKey))&&(isset($this->newFieldNameMap[$key]))){
					$createQuery .= '`'.$this->newFieldNameMap[$key].'`="'.$this->checkMySqlSpecialFunction($value).'",';
				}
			}
			$createQuery = substr($createQuery,0,-1);
		}
		if(!$db->insert($createQuery)){
			return false;
		}
		if(count($this->primaryKey)==1){
			$lastInsertId = (int)$db->getLastInsertId();
			if($lastInsertId!==0){
				$this->data[$this->primaryKey[0]] = $lastInsertId;
			}
		}
		return true;
	}
	
	
	/**
	 * Function - Delete
	 * @return mixed
	 */
	public function delete() {
		if(isset($this->newFieldNameMap['is_deleted'])) {
			$this->data['is_deleted'] = 1;
			return $this->save();
		} else {
			$db = \nano\core\db\core\Database::open($this->dbConfig);
			$db->setDefaultDatabase($this->dbName);
			if($db->isOpen()) {
				$deleteQuery = 'DELETE FROM `'.$this->tableName.'` WHERE ';
				foreach($this->primaryKey as $primaryKey){
					$deleteQuery .= '`'.$primaryKey.'`="'.addslashes($this->data[$primaryKey]).'" AND ';
				}
				$deleteQuery = substr($deleteQuery,0,-4).'LIMIT 1';
				//on delete flush cache keys
				if(\nano\core\config\Config::get('caching')==true){
					$this->flushCacheAgainstCacheReferenceKeys();
				}
				return $db->delete($deleteQuery);
			} else {
				throw new \nano\core\exception\DatabaseException('Failed to open a connection to the database...');
			}
		}
		return false;
	}
	
	/**
	 * Function - Get Database Config
	 * @return string
	 */
	public function getDatabaseConfig(){
		return $this->dbConfig;
	}
	
	/**
	 * Function - Get Model Name
	 * @return mixed
	 */
	public function getModelName(){
		return $this->modelName;
	}
	
	/**
	 * Function - Get Primary Key
	 * @return mixed
	 */
	public function getPrimaryKey(){
		return $this->primaryKey;
	}
	
	/**
	 * Function - Get Database Name
	 * @return mixed
	 */
	public function getDatabaseName(){
		return $this->dbName;
	}
	
	/**
	 * Function - Get Table Name
	 * @return mixed
	 */
	public function getTableName(){
		return $this->tableName;
	}
	
	/**
	 * Function - Get Data Array
	 * @return mixed
	 */
	public function getDataArray(){
		return $this->data;
	}
	
	/**
	 * Function - Get Field Information
	 * @return mixed
	 */
	public function getFieldInformation(){
		return $this->fields;
	}
}