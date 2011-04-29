<?php
namespace nano\core\db;

/**
 * ORM class 
 * 
 * ORM
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.db
 */
class ORM {
	protected static $instance = null;
	protected $database = null;
	protected $tableInstances = array();
	
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Function - Get Instance
	 */
	public static function getInstance() {
		if(self::$instance==null){
			$databases = \nano\core\config\Config::get('databases');
			self::$instance = new self();
			self::$instance->database = $databases['default']['name'];
		}
		return self::$instance;
	}
	
	/**
	 * Function - Set Database
	 * @param mixed $database Database
	 */
	public function setDatabase($database) {
		$this->database = $database;
	}
	
	/**
	 * Function - Get Table
	 * @param mixed $name Name
	 * @param mixed $database=null Database=null
	 * @return mixed
	 */
	public function getTable($name,$database=null) {
		$database = ($database==null)? $this->database : $database;
		$newClassName = 'project\db\om\\'.$database.'\\'.$name.'Table';
		foreach($this->tableInstances as $className => $tableInstance){
			if($className==$newClassName){
				return $tableInstance;
			}
		}
		$tableInstance = new $newClassName();
		$this->tableInstances[get_class($tableInstance)] = $tableInstance;
		return $tableInstance;
	}
}