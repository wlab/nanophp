<?php
namespace nano\core\config;


/**
 * Core configuration.
 * Extended by project\config\Config, which contains project-specific options.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.config
 */
class Config {
	private static $environment = null;
	
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	
	/**
	 * Set the environment.
	 * If $environment is null or not provided, setEnvironment() will try to use the environment
	 * variable PROJECT_ENV supplied by the web server. If this is not set, the default
	 * environment 'Live' will be used.
	 * 
	 * @param string $environment Name of the environment to use.
	 */
	final public static function setEnvironment($environment=null) {
		if($environment===null){
			self::$environment = isset($_SERVER['PROJECT_ENV'])? $_SERVER['PROJECT_ENV'] : 'Live';
		}else{
			self::$environment = $environment;
		}
		return true;
	}
	
	/**
	 * Set the error reporting function to use, as specified in the environment settings.
	 * Called by index.php.
	 */
	final public static function setErrorReporting() {
		$errorFunction = self::get('errors');
		if($errorFunction!=null)
		{
			\project\config\PHPErrors::$errorFunction();
		} else {
			throw new \nano\core\exception\CoreException('Unable to set error reporting level. The \'errors\' setting appears to be missing in the environment ('.self::$environment.') configuration file');
		}
	}
	
	/**
	 * Set the default exception handing function to \Config\defaultExceptionHandler().
	 * Called by index.php
	 */
	final public static function setDefaultExceptionHandler() {
		set_exception_handler(__CLASS__.'::defaultExceptionHandler');
	}
	
	/**
	 * Function - Default Exception Handler
	 * TODO:APC: $e can only ever be an \Exception right?
	 * @param mixed $e E
	 */
	public static function defaultExceptionHandler($e) {
		$log = \nano\core\log\Log::getInstance();
		$log->addError('Exception',$e);
		///TODO:APC: Unnecessary cast.
		if((bool)\ini_get('display_errors')){
			$exceptionWidget = new \project\globals\widgets\exception\Exception();
			$exceptionWidget->setParentException($e);
			echo $exceptionWidget->getRenderedWidget();
		}
	}
	
	// final public static function setDefaultShutdownHandler() {
	// 		register_shutdown_function(__CLASS__.'::defaultShutdownHandler');
	// 	}
	// 	
	// 	public static function defaultShutdownHandler() {
	// 		$error = error_get_last();
	// 		if($error!==null){
	// 			ob_start();
	// 
	// 			ob_flush();
	// 		}
	// 	}
	
	/**
	 * Get the name of the current environment.
	 * @return string Current environment name.
	 */
	final public static function getEnvironment() {
		return self::$environment;
	}
	
	
	/**
	 * Get the value of a configuration variable in the current environment.
	 * @param string $name Name of the configuration variable to retrieve.
	 * @return mixed Value of requested variable, or NULL if that variable does not exist.
	 */
	final public static function get($name) {
		$env = 'project\config\environments\\'.self::$environment;
		return isset($env::$config[$name])? $env::$config[$name] : null;
	}
	
	/**
	 * Set the value of a configuration variable in the current environment.
	 * @param string $name Name of the variable to set.
	 * @param mixed $value Value to assign.
	 */
	final public static function set($name,$value) {
		$env = 'project\config\environments\\'.self::$environment;
		$env::$config[$name] = $value;
	}
	
	
	/**
	 * Get the whole configuration array for the current environment.
	 * @return array
	 */
	final public static function getConfig() {
		$env = 'project\config\environments\\'.self::$environment;
		return $env::$config;
	}
	
	/**
	 * Method stub to be extended when needed.
	 * Called by nano\core\routing\Routing.
	 */
	public static function bootStrap(){ }
}