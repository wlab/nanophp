<?php
namespace nano\core\web;

/**
 * Request class.
 * The singleton instance provides details details of the request as received from the web server.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.web
 */
class Request {
	
	private static $instance = null;
	
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Get the singleton instance of the Request class.
	 * @return Request Instance.
	 */
	public static function getInstance() {
		if(self::$instance===null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Get a once only cookie with the given name.
	 * @param string $name Name of the cookie to retrieve. Note that cookies are automatically prefixed with 'nano_ooc_'.
	 * @return mixed Data from the cookie, or NULL if the cookie was not available.
	 */
	public function getOnceOnlyCookie($name) {
		return isset($_COOKIE['nano_ooc_'.$name])? unserialize(html_entity_decode($_COOKIE['nano_ooc_'.$name],ENT_QUOTES,'UTF-8')) : null;
	}
	
	/**
	 * Get the value of a cookie.
	 * @param string $name Name of the cookie to retrieve.
	 * @param string $defaultValue A default value to return if the cookie is not available.
	 * @return string Value of the cookie, or $defaultValue if the cookie could not be retrieved.
	 */
	public function getCookie($name,$defaultValue=null) {
		return isset($_COOKIE[$name])? $_COOKIE[$name] : $defaultValue;
	}
	
	/**
	 * Get a parameter provided via the GET method.
	 * TODO:APC: Explain the above better!
	 * @param string $name Name of the parameter requested.
	 * @param string $defaultValue A default value to return if the parameter was not set.
	 * @return string Value of the requested parameter, or $defaultValue if the parameter was not set.
	 */
	public function getParameter($name,$defaultValue=null){
		return isset($_GET[$name])? $_GET[$name] : $defaultValue;
	}
	
	/**
	 * Get a parameter provided via the POST method.
	 * @param string $name Name of the parameter requested.
	 * @param string $defaultValue A default value to return if the parameter was not set.
	 * @return string Value of the requested parameter, or $defaultValue if the parameter was not set.
	 */
	public function getPostParameter($name,$defaultValue=null){
		return isset($_POST[$name])? $_POST[$name] : $defaultValue;
	}
	
	/**
	 * Get a value from the $_SERVER autoglobal.
	 * @param string $name Key for the value requested.
	 * @param string $defaultValue A default value to return if the requested value is not set.
	 * @return string The requested value, or $defaultValue if the key does not exist.
	 */
	public function getHeader($name,$defaultValue=null){
		return isset($_SERVER[$name])? $_SERVER[$name] : $defaultValue;
	}
	
	/**
	 * Check whether the request method is POST.
	 * @return bool TRUE if the POST method was used, otherwise FALSE.
	 */
	public function isPost(){
		return (strtoupper($this->getHeader('REQUEST_METHOD'))=='POST')? true : false;
	}
	
	/**
	 * Check whether the request method is PUT.
	 * @return bool TRUE if the PUT method was used, otherwise FALSE.
	 */
	public function isPut(){
		return (strtoupper($this->getHeader('REQUEST_METHOD'))=='PUT')? true : false;
	}
	
	/**
	 * Check whether the request method is DELETE.
	 * @return bool TRUE if the DELETE method was used, otherwise FALSE.
	 */
	public function isDelete(){
		return (strtoupper($this->getHeader('REQUEST_METHOD'))=='DELETE')? true : false;
	}
}