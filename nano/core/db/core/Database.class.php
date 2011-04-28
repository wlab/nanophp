<?php
namespace nano\core\db\core;


/**
 * Database interface.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.db.core
 */
class Database {
	
	const DB_MODE_SINGLE = 1;
	const DB_MODE_REPLICATION = 2;
	const DB_MODE_CLUSTER = 3;
	
	/**
	 * Escape a string for use in an SQL query.
	 * @param string $value String to be escaped.
	 * @return string Escaped version of the input.
	 */
	private static function escape($value) { return addslashes($value); }
	private static $dbInstances = null;

	private $dbData = null;		//Database connection info from config file.
	private $dbObject = null;	/*Pointer to the mysqli instance. In 'replication' mode, this may be an array, in which case all writes must be done through the server at index 0. */	
	private $connected = false;
	private $lastInsertId = null;
	private $defaultDatabaseName = null;
	private $dbAliases = null;
	private $host = null;
	private $port = null;
	
	
	/**
	 * Open a connection to the MySQL server(s).
	 * If $name is not specified, the function will attempt to use the configuration named 'default', if it exists.
	 * A DatabaseException will be thrown on failure.
	 * @param mixed $name Name of database configuration to use as defined in project configuration.
	 * @return Database A singleton interface to a Database object associated with the specified configuration.
	 */
	public static function open($name='default') {
		if(!isset(self::$dbInstances[$name])){
			self::$dbInstances[$name] = new Database($name);
		}
		return self::$dbInstances[$name];
	}
	
	/**
	 * Function - __construct
	 * @param mixed $name='default' Name='default'
	 */
	final private function __construct($name='default') {
		$this->dbData = \project\config\Config::get('databases');
		if(isset($this->dbData[$name])){
			$this->dbAliases = isset($this->dbData[$name]['database_aliases'])? $this->dbData[$name]['database_aliases'] : array();
			//Set the default database to connect to on this instance.
			//When changing database on the connection see 'setDefaultDatabase()' below ...
			$this->defaultDatabaseName = $this->dbData[$name]['name'];
			$databaseAlias = isset($this->dbAliases[$this->defaultDatabaseName])? $this->dbAliases[$this->defaultDatabaseName] : $this->defaultDatabaseName;
			
			switch($this->dbData[$name]['mode']){
				case self::DB_MODE_SINGLE:
					//Connect to one instance.
					$this->host = $this->dbData[$name]['servers'][0]['host'];
					$this->port = $this->dbData[$name]['servers'][0]['port'];
					$this->dbObject = new \mysqli(
						'p:'.$this->host,
						$this->dbData[$name]['servers'][0]['user'],
						$this->dbData[$name]['servers'][0]['pass'],
						$databaseAlias,
						$this->port
					);
					if($this->dbObject->connect_errno=="0") {
						$this->connected = true;
					} else {
						throw new \nano\core\exception\DatabaseException('There is no open connection to a database ('.$this->dbObject->connect_error.').');
					}
					break;
				case self::DB_MODE_REPLICATION:
					break;
				case self::DB_MODE_CLUSTER:
					break;
				default:
					///TODO:APC: Other modes not supported yet.
			}
		}else{
			throw new \nano\core\exception\DatabaseException('The requested database configuration ('.$name.') could not be found.');
		}
	}
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Set the character set to be used for the database server connection.
	 * @param string $charSet Chosen character set.
	 * @return boolean TRUE on success. Throws a DatabaseException on failure.
	 */
	public function setCharSet($charSet){
		if(!$this->dbObject->set_charset($charSet)){
			throw new \nano\core\exception\DatabaseException($this->dbObject->error);
			return false; ///TODO:APC: This is redundant!
		}
		return true;
	}
	
	/**
	 * Set the name of the default database to use in this instance.
	 * @param string $defaultDatabaseName Default Database Name
	 */
	public function setDefaultDatabase($defaultDatabaseName){
		if($this->defaultDatabaseName != $defaultDatabaseName){
			$databaseAlias = isset($this->dbAliases[$this->defaultDatabaseName])? $this->dbAliases[$this->defaultDatabaseName] : $this->defaultDatabaseName;
			$query = 'USE `'.$databaseAlias.'`';
			\nano\core\log\Log::getInstance()->addQuery('[Database: '.$this->defaultDatabaseName.' | Host: '.$this->host.':'.$this->port.'] - '.$query);
			$this->dbObject->query($query);
			$this->defaultDatabaseName = $defaultDatabaseName;
		}
	}
	
	
	/**
	 * Perform the given SELECT query.
	 * Synonym for get().
	 * @param string $query SQL statement to execute.
	 * @return array An associative array of results. If the result set is empty, returns an empty array.
	 */
	public function select($query) {
		return $this->get($query);
	}
	
	
	/**
	 * Perform the given INSERT query.
	 * 
	 * @param string $query SQL statement to execute.
	 * @return mixed
	 */
	public function insert($query) {
		if($this->execute($query)){
			$this->lastInsertId = $this->dbObject->insert_id;
			return true;
		}
		return false;
	}
	
	
	/**
	 * Perform the given UPDATE query.
	 * 
	 * @param string $query SQL statement to execute.
	 */
	public function update($query) {
		return $this->execute($query);
	}
	
	
	/**
	 * Perform the given DELETE query.
	 * 
	 * @param string $query SQL statement to execute.
	 */
	public function delete($query) {
		return $this->execute($query);
	}
	
	
	/**
	 * Perform the given write query.
	 * Throws a DatabaseException on failure.
	 * @param string $query SQL statement to execute.
	 * @return boolean TRUE.
	 */
	public function execute($query) {
		///TODO:APC: This returns true, and only ever true. (Unless it's abused for a READ, in which case it will
		/// return a result set!) Just a little weird...
		\nano\core\log\Log::getInstance()->addQuery('[Database: '.$this->defaultDatabaseName.' | Host: '.$this->host.':'.$this->port.'] - '.$query);
		if(!$this->connected){
			throw new \nano\core\exception\DatabaseException('There is no open connection to a database');
		}
		$result = $this->dbObject->query($query);
		if(!$result){
			throw new \nano\core\exception\DatabaseException($this->dbObject->error);
		}
		return $result;
	}
	
	/**
	 * Perform the given read query.
	 * Throws a DatabaseException on failure.
	 * @param string $query SQL statement to execute.
	 * @return array An associative array of results. If the result set is empty, returns an empty array.
	 */
	private function get($query) {
		$output = array();
		\nano\core\log\Log::getInstance()->addQuery('[Database: '.$this->defaultDatabaseName.' | Host: '.$this->host.':'.$this->port.'] - '.$query);
		if(!$this->connected){
			throw new \nano\core\exception\DatabaseException('There is no open connection to a database');
		}
		$result = $this->dbObject->query($query);
		if(!$result){
			throw new \nano\core\exception\DatabaseException($this->dbObject->error);
		}
		while($row = $result->fetch_assoc()){
			$output[] = $row;
		}
		$result->free();
		return $output;
	}
	
	
	/**
	 * Get the status of this database object.
	 * @return boolean TRUE if currently connect, otherwise FALSE.
	 */
	public function isOpen() {
		return $this->connected;
	}
	
	
	/**
	 * Get the value of the primary key assigned during the last INSERT query performed on this database object.
	 * IMPORTANT: The value will only be returned for queries performed using the insert() function, NOT the execute() function,
	 * which will otherwise perform identically.
	 * @return mixed Value of primary key.
	 */
	public function getLastInsertId() {
		return $this->lastInsertId;
	}
}