<?php
namespace nano\core\db\om;


/**
 * BaseTable class 
 * 
 * Base Table
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.db.om
 */
class BaseTable {
	
	protected $modelName = null;
	protected $primaryKey = null;
	protected $dbConfig = null;
	protected $dbName = null;
	protected $tableName = null;
	protected $fields = array();
	protected $newFieldNameMap = array();
	
	
	/**
	 * Function - Do Raw Select
	 * @param mixed $query Query
	 * @return mixed
	 */
	public function doRawSelect($query){
		$db = \nano\core\db\core\Database::open($this->dbConfig);
		$db->setDefaultDatabase($this->dbName);
		if($db->isOpen()){
			return $db->select($query);
		} else {
			throw new \nano\core\exception\DatabaseException('Failed to open a connection to the database...');
		}
	}
	
	/**
	 * Function - Do Select
	 * @param mixed $query Query
	 * @param  $cacheKey=null Cache Key=null
	 * @param  $cacheExpires=\nano\core\cache\Memcached::EXPIRES_30MINS Cache Expires=\nano\core\cache\Memcached::EXPIRES_30MINS
	 * @return mixed
	 */
	public function doSelect($query, $cacheKey=null, $cacheExpires=\nano\core\cache\Memcached::EXPIRES_30MINS) {
		if($cacheKey!==null){
			if(\nano\core\config\Config::get('caching')==true){
				$memcached = \nano\core\cache\Memcached::getInstance($this->dbConfig);
				\nano\core\log\Log::getInstance()->addCachedInfo('Select using Cache Key: '.$memcached->getKeyPrefix().'-'.$cacheKey.' (Expires: '.$cacheExpires.')');
				if(false!==($objs = $memcached->get($cacheKey))){
					\nano\core\log\Log::getInstance()->addCachedInfo('Found using Cache Key: '.$memcached->getKeyPrefix().'-'.$cacheKey.' (Expires: '.$cacheExpires.')');
					return unserialize($objs);
				}
			}
		}
		$db = \nano\core\db\core\Database::open($this->dbConfig);
		$db->setDefaultDatabase($this->dbName);
		if($db->isOpen()){
			$objs = array();			
			if(is_object($query)){
				if($query instanceof \nano\core\db\core\SelectQuery) {
					$query->setDatabase($this->dbName);
					$query = $query->getQuery();
					$results = $db->select($query);
					foreach($results as $result){
						$obj = array();
						$modelNames = array();
						foreach($result as $key => $value){
							if(preg_match('/^(.+?)[\_]{3}(.+)/',$key,$matches)){
								$modelName = $matches[1];
								$columnName = $matches[2];
								$hydratedObject = 'project\db\om\\'.$this->dbName.'\\'.$modelName;
								if(!array_key_exists($modelName,$obj)){
									$modelNames[] = $modelName;
									$obj[$modelName] = new $hydratedObject();
								}
								$obj[$modelName]->{$columnName} = $value;
							}
						}
						foreach($modelNames as $modelName){
							if($cacheKey!==null) {
								if(\nano\core\config\Config::get('caching')==true){
									$obj[$modelName]->addCacheReferenceKey($cacheKey);
								}
							}
						}
						$objs[] = $obj;
					}
				} else {
					throw new \nano\core\exception\CoreException('The $query object passed into doSelect(...) was not and instance of \nano\core\db\core\SelectQuery');
				}
			} else {
				$hydratedObject = 'project\db\om\\'.$this->dbName.'\\'.$this->modelName;
				$results = $db->select($query);
				foreach($results as $result){
					$obj = new $hydratedObject();
					foreach($result as $key => $value){
						$fieldName = $key;
						if(isset($this->fields[$fieldName])){
							if($this->fields[$fieldName]['is_foreign_reference']){
								$fieldName = $this->fields[$fieldName]['use_model'];
							}
						}
						$obj->{$fieldName} = $value;
					}
					if($cacheKey!==null) {
						if(\nano\core\config\Config::get('caching')==true){
							$obj->addCacheReferenceKey($cacheKey);
						}
					}
					$objs[] = $obj;
				}
			}
			if($cacheKey!==null) {
				if(\nano\core\config\Config::get('caching')==true){
					\nano\core\log\Log::getInstance()->addCachedInfo('Set using Cache Key: '.$memcached->getKeyPrefix().'-'.$cacheKey.' (Expires: '.$cacheExpires.')');
					$memcached->set($cacheKey,serialize($objs),$cacheExpires);
				}
			}
			return $objs;
		} else {
			throw new \nano\core\exception\DatabaseException('Failed to open a connection to the database...');
		}
	}
	
	/**
	 * Function - Truncate Table
	 */
	public function truncateTable(){
		$db = \nano\core\db\core\Database::open($this->dbConfig);
		$db->setDefaultDatabase($this->dbName);
		if($db->isOpen()){
			$db->execute('TRUNCATE TABLE `'.$this->tableName.'`');
		} else {
			throw new \nano\core\exception\DatabaseException('Failed to open a connection to the database...');
		}
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
	 * Function - Get Field Information
	 * @return mixed
	 */
	public function getFieldInformation(){
		return $this->fields;
	}
	
	/**
	 * Function - Get New Field Name Map Information
	 * @return mixed
	 */
	public function getNewFieldNameMapInformation(){
		return $this->newFieldNameMap;
	}
}