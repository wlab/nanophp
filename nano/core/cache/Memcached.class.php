<?php
namespace nano\core\cache;


/**
 * Memcached class 
 * 
 * Memcached
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package core
 * @subpackage cache
 */
class Memcached {
	const EXPIRES_30MINS = 1800;
	const EXPIRES_60MINS = 3600;
	const EXPIRES_90MINS = 5400;
	const EXPIRES_3HOURS = 10800;
	const EXPIRES_6HOURS = 21600;
	const EXPIRES_12HOURS = 43200;
	const EXPIRES_24HOURS = 86400;
	
	protected static $instance = array();
	private $keyPrefix = '';
	private $memcached = null;
	private $memcachedConstants = array();
	private $instanceName = null;
	
	/**
	 * Function - __construct
	 */
	final private function __construct($instanceName) {
		$this->instanceName = $instanceName;
		$this->memcached = new \Memcached();
		$reflect = new \ReflectionClass($this->memcached);
		$this->memcachedConstants = $reflect->getConstants();
		$servers = array();
		$memcacheInstances = \nano\core\config\Config::get('memcache_servers');
		foreach($memcacheInstances[$instanceName] as $memcacheHostName => $memcachePort){
			$server = array();
			$server[] = (string)$memcacheHostName;
			$server[] = (int)$memcachePort;
			$servers[] = $server;
		}
		$this->memcached->addServers($servers);
		//doing some reading on the memcache website, addservers should be used instead of recurse on add server?!
	}
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	
	/**
	 * Function - Get Instance
	 */
	public static function getInstance($instanceName='default'){
		if(!in_array($instanceName,self::$instance)){
			//create default pool
			self::$instance[$instanceName] = new self($instanceName);
		}
		return self::$instance[$instanceName];
	}
	
	/**
	 * Function - Get Key
	 * @param mixed $keys Keys
	 */
	private function getKey($keys) {
		$str = $this->keyPrefix;
		if(is_array($keys)){
			foreach($keys as $key){
				$str .= '-'.$key;
			}
			return $str;
		}
		return $str.'-'.$keys;
	}
	
	
	/**
	 * Function - Set Key Prefix
	 * @param mixed $keyPrefix Key Prefix
	 */
	public function setKeyPrefix($keyPrefix){
		$this->keyPrefix = $keyPrefix;
	}
	
	/**
	 * Function - Get Key Prefix
	 * @return mixed
	 */
	public function getKeyPrefix(){
		return $this->keyPrefix;
	}
	
	/**
	 * Function - Set
	 * @param mixed $keys Keys
	 * @param mixed $value Value
	 * @param mixed $expires=0 Expires=0
	 * @return mixed
	 */
	public function	set($keys,$value,$expires=0){		
		$key = $this->getKey($keys);
		\nano\core\log\Log::getInstance()->addCachedInfo('[Cache Pool: '.$this->instanceName.'] Set: '.$key);
		return $this->memcached->set($key,$value,$expires);
	}
	
	/**
	 * Function - Get
	 * @param mixed $keys Keys
	 * @return mixed
	 */
	public function get($keys){
		$key = $this->getKey($keys);
		\nano\core\log\Log::getInstance()->addCachedInfo('[Cache Pool: '.$this->instanceName.'] Get: '.$key);
		return $this->memcached->get($key);
	}
	
	/**
	 * Function - Delete
	 * @param mixed $keys Keys
	 * @return mixed
	 */
	public function delete($keys){		
		$key = $this->getKey($keys);
		\nano\core\log\Log::getInstance()->addCachedInfo('[Cache Pool: '.$this->instanceName.'] Delete: '.$key);
		return $this->memcached->delete($key);
	}
	
	/**
	 * Function - Flush
	 * @return mixed
	 */
	public function flush(){
		\nano\core\log\Log::getInstance()->addCachedInfo('[Cache Pool: '.$this->instanceName.'] Flush: Flushed Cache');
		return $this->memcached->flush();
	}
	
	/**
	 * Function - Get Result Code
	 * @return mixed
	 */
	public function getResultCode(){
		return (int)$this->memcached->getResultCode();
	}
	
	/**
	 * Function - Get Value From Memcache Constant
	 * @param mixed $name Name
	 * @return mixed
	 */
	public function getValueFromMemcacheConstant($name){
		return isset($this->memcachedConstants[$name])? $this->memcachedConstants[$name] : null;
	}
	
}