<?php
namespace nano\core\log;

/**
 * Log class 
 * 
 * Log
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.log
 */
class Log {
	const PRIORITY_LEVEL_DEVLOG = 0;
	const PRIORITY_LEVEL_LOGFILE = 1;
	const PRIORITY_LEVEL_EMAIL = 2;
	const PRIORITY_LEVEL_SMS = 3;
	
	protected static $instance = null;
	private $queries = null;
	private $errors = null;
	private $cachedInfo = null;
	private $startTime = null;
	private $errorPriorityLevel = null;
	
	/**
	 * Function - __construct
	 */
	final private function __construct() {
		$this->queries = array();
		$this->errors = array();
		$this->cachedInfo = array();
		switch(\project\config\Config::get('logging_priority')){
			case self::PRIORITY_LEVEL_LOGFILE:
				$this->errorPriorityLevel = self::PRIORITY_LEVEL_LOGFILE;
				break;
			case self::PRIORITY_LEVEL_EMAIL:
				$this->errorPriorityLevel = self::PRIORITY_LEVEL_EMAIL;
				break;
			case self::PRIORITY_LEVEL_SMS:
				$this->errorPriorityLevel = self::PRIORITY_LEVEL_SMS;
				break;
			default:
				$this->errorPriorityLevel = self::PRIORITY_LEVEL_DEVLOG;
				break;
		}
	}
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Get a singleton instance of the logger.
	 */
	public static function getInstance() {
		if(self::$instance==null){
			self::$instance = new self();
			self::$instance->startTimer();
		}
		return self::$instance;
	}
	
	/**
	 * Start the execution timer.
	 */
	public function startTimer() {
		///TODO:APC: Is there any reason not to just use microtime(true)?
		$time = microtime();
		$time = explode(' ', $time);
		$this->startTime = $time[1] + $time[0];
	}
	
	/**
	 * Get the time since the last call to startTimer().
	 * @return float Time in milliseconds.
	 */
	public function getExecuteTime() {
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		return ($time - $this->startTime)*1000;
	}
	
	/**
	 * Add an SQL query to the log.
	 * @param string $query SQL Statement.
	 */
	public function addQuery($query) {
		$this->queries[] = $query;
	}
	
	/**
	 * Get the SQL queries that have been logged.
	 * @return array Array of SQL statement strings.
	 */
	public function getQueries() {
		return $this->queries;
	}
	
	/**
	 * Add information to the cache log.
	 * @param string $info Human-readable information string.
	 */
	public function addCachedInfo($info) {
		$this->cachedInfo[] = $info;
	}
	
	/**
	 * Get the cache log.
	 * @return array Array of strings in the cache log.
	 */
	public function getCachedInfos() {
		return $this->cachedInfo;
	}
	
	/**
	 * Set the default error priority level.
	 * Errors logged that have not been assigned their own priority will use this default.
	 * @param int $errorPriorityLevel Default priority level.
	 */
	public function setErrorPriorityLevel($errorPriorityLevel) {
		$this->errorPriorityLevel = $errorPriorityLevel;
	}
	
	/**
	 * Add an error to the error log.
	 * If $fileName is not specified, the name of the log file will be determined based on the
	 * $type argument. If the $filePath is not specified, NanoPHP will attempt to use the path set
	 * in the 'logging_path' config variable, or if that is not specified, the system's temporary
	 * directory.
	 * @param string $type Human-readable string defining type of error.
	 * @param mixed $obj An \Exception object related to the error, or human-readable string describing the error.
	 * @param int $priority Error priority level. If not specified, the class default will be used.
	 * @param string $fileName Name of log file to write to.
	 * @param string $filePath Path of log file file to write to.
	 */
	public function addError($type,$obj,$priority=null,$fileName=null,$filePath=null) {
		$stackTrace = null;
		if($obj instanceof \Exception){
			$message = $obj->getMessage();
			$stackTrace = 'Thrown exception of type '.$type.' in '.$obj->getFile().' on line #'.$obj->getLine()."\n\n";
			$stackTrace .= $obj->getTraceAsString();
		} else {
			$message = $obj;
		}
		$priority = ($priority===null)? $this->errorPriorityLevel : $priority;
		switch($priority){
			case self::PRIORITY_LEVEL_LOGFILE:
				$this->appendToLogFile($type,$message,$stackTrace,$fileName,$filePath);
				break;
			case self::PRIORITY_LEVEL_EMAIL:
				$this->appendToLogFile($type,$message,$stackTrace,$fileName,$filePath);
				$this->sendImmediatelyViaEmail($type,$message,$stackTrace);
				break;
		}
		$this->errors[] = array(
			'type' => $type,
			'message' => $message,
			'stackTrace' => $stackTrace
		);
	}
	
	/**
	 * Get the error log.
	 * @return array Array of strings in the error log.
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Append the given message to a log file.
	 * @param string $type Human-readable string defining type of error.
	 * @param string $message Human-readable message giving details of the error.
	 * @param string $stackTrace 
	 * @param string $fileName Name of log file to write to.
	 * @param string $filePath Path of log file file to write to.
	 */
	private function appendToLogFile($type,$message,$stackTrace=null,$fileName=null,$filePath=null) {
		$stackTrace = ($stackTrace==null)? 'No stack trace given...' : $stackTrace;
		if($fileName==null){
			$fileName = preg_replace('/[^\w\d]+/','-',$type).'.log';
		}
		if($filePath===null){
			$filePath = \project\config\Config::get('logging_path');
			if($filePath===null){
				$filePath = sys_get_temp_dir();
			}
		}
		
		if(preg_match('/\/$/',$filePath)){
			$filePath = $filePath.'nanophp/';
		} else {
			$filePath = $filePath.'/nanophp/';
		}
		if(!file_exists($filePath)){
			mkdir($filePath,0777,true);
		}
		file_put_contents($filePath.$fileName,date('Y-m-d H:i:s').':-:'.$type.':-:'.$message.':-:'.$stackTrace.':-:'."\n",FILE_APPEND);
	}
	
	/**
	 * Function - Send Immediately Via Email
	 * @param mixed $type Type
	 * @param mixed $message Message
	 * @param mixed $stackTrace Stack Trace
	 */
	private function sendImmediatelyViaEmail($type,$message,$stackTrace) {
		$emails = \project\config\Config::get('logging_send_to');
		if(is_array($emails)){
			foreach($emails as $email){
				$domain = \nano\core\routing\Routing::getInstance()->getDomain();
				$headers = 'From: exception@'.$domain."\r\n".'Reply-To: exception@'.$domain."\r\n".'X-Mailer: PHP/'.phpversion();
				mail($email, $type.' - '.$message, 'Type: '.$type."\n\nMessage: ".$message."\n\n".$stackTrace, $headers);
			}
		}
	}
}