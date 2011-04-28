<?php
namespace nano\core\config;

/**
 * Various pre-defined PHP configurations for different levels of error reporting.
 * The configuration to use is specified in environment settings, and called by
 * nano\core\config\Config on initialisation.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.config
 */
class PHPErrors {
	
	/**
	 * Configure PHP's error-reporting functionality for development use.
	 */
	public static function dev(){
		ini_set('error_reporting', E_ALL | E_STRICT);
		ini_set('display_errors', true);
		ini_set('display_startup_errors', true);
		ini_set('log_errors', true);
		ini_set('ignore_repeated_errors', false);
		ini_set('ignore_repeated_source', false);
		ini_set('report_memleaks', true);
		ini_set('track_errors', true);
		ini_set('html_errors', true);		
	}
	
	/**
	 * Configure PHP's error-reporting functionality for staging use.
	 */
	public static function staging(){
		ini_set('error_reporting', E_ALL | E_STRICT);
		ini_set('display_errors', true);
		ini_set('display_startup_errors', true);
		ini_set('log_errors', true);
		ini_set('ignore_repeated_errors', false);
		ini_set('ignore_repeated_source', false);
		ini_set('report_memleaks', true);
		ini_set('track_errors', true);
		ini_set('html_errors', true);
	}
	
	/**
	 * Configure PHP's error-reporting functionality for production use.
	 */
	public static function live(){
		ini_set('error_reporting', E_ALL | E_STRICT);
		ini_set('display_errors', false);
		ini_set('display_startup_errors', false);
		ini_set('log_errors', true);
		ini_set('ignore_repeated_errors', false);
		ini_set('ignore_repeated_source', false);
		ini_set('report_memleaks', false);
		ini_set('track_errors', false);
		ini_set('html_errors', false);
	}
	
	/**
	 * Do not modify any settings - the defaults in php.ini will be used.
	 */
	public static function ini(){ }
	
}