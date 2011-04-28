<?php
namespace nano\core\autoloader;

/**
 * Autoloader class 
 * 
 * Autoloader
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package core
 * @subpackage autoloader
 */
class Autoloader {
	
	
	/**
	 * Function - Get Path From Name Space
	 * @param mixed $nameSpace Name Space
	 * @return mixed
	 */
	public static function getPathFromNameSpace($nameSpace) {
		if(!preg_match('/^(?:project|nano)\\\\/',$nameSpace)) {
			return $_SERVER['SCRIPTS_LOAD_FROM'].'plugins/'.$nameSpace.'/lib/'.$nameSpace.'.php';
		} else {
			return $_SERVER['SCRIPTS_LOAD_FROM'].str_replace('\\','/',$nameSpace).'.class.php';
		}
	}
	
	/**
	 * Function - Is File
	 * @param mixed $nameSpace Name Space
	 * @return mixed
	 */
	public static function isFile($nameSpace) {
		if(file_exists(self::getPathFromNameSpace($nameSpace))) {
			return true;
		}
		return false;
	}
	
	/**
	 * Function - Load
	 * @param mixed $nameSpace Name Space
	 */
	public static function load($nameSpace) {
		require_once(self::getPathFromNameSpace($nameSpace));
	}
	
	
	/**
	 * Function - Register
	 */
	public static function register() {
		require_once($_SERVER['SCRIPTS_LOAD_FROM'].'nano/core/config/Config.class.php');
		require_once($_SERVER['SCRIPTS_LOAD_FROM'].'project/config/Config.class.php');
		if(isset($_SERVER['PROJECT_APP'])){
			require_once($_SERVER['SCRIPTS_LOAD_FROM'].'project/apps/'.$_SERVER['PROJECT_APP'].'/Config.class.php');
			$customConfigClassName = 'project\apps\\'.$_SERVER['PROJECT_APP'].'\Config';
			$autoloads = $customConfigClassName::$autoloads;
		} else {
			$configClassName = 'project\config\Config';
			$autoloads = $configClassName::$autoloads;
		}
		foreach($autoloads as $autoload){
			require_once($_SERVER['SCRIPTS_LOAD_FROM'].$autoload['include_path']);
			$className = $autoload['class_name'];
			$functionName = $autoload['call_function_name'];
			switch($autoload['call_type']){
				case 'static':
					$className::$functionName();
					break;
				default:
					$class = new $className();
					$class->$functionName();
					break;
			}
		}
		//push this to the bottom of the stack - can't rely on prepending on spl function
		spl_autoload_register(__CLASS__.'::load');
	}
	
	
	/**
	 * Function - Configure
	 */
	public static function configure() {
		$customConfigClassName = 'project\apps\\'.$_SERVER['PROJECT_APP'].'\Config';
		$autoloads = $customConfigClassName::$autoloads;
		foreach($autoloads as $autoload){
			if(isset($autoload['config_class_name'])){
				$className = $autoload['config_class_name'];
				$functionName = $autoload['config_call_function_name'];
				switch($autoload['config_call_type']){
					case 'static':
						$className::$functionName();
						break;
					default:
						$class = new $className();
						$class->$functionName();
						break;
				}
			}
		}
	}
}