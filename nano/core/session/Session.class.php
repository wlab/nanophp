<?php
namespace nano\core\session;

/**
 * Session class 
 * 
 * Session
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.session
 */
class Session {
	
	protected static $user = null;
	protected static $isSession = false;
	
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Function - Get User
	 * @return mixed
	 */
	final public static function getUser() {
		return self::$user;
	}
	
	/**
	 * Function - Is Session
	 * @return mixed
	 */
	final public static function isSession() {
		return self::$isSession;
	}
	
	/**
	 * Function - Set Attribute
	 * @param mixed $key Key
	 * @param mixed $value Value
	 * @param mixed $expires=0 Expires=0
	 * @param mixed $path='/' Path='/'
	 */
	public static function setAttribute($key,$value,$expires=0,$path='/') {
		setcookie($key,$value,$expires,$path);
	}
	
	/**
	 * Function - Get Attribute
	 * @param mixed $key Key
	 * @return mixed
	 */
	public static function getAttribute($key) {
		return isset($_COOKIE[$key])? $_COOKIE[$key] : null;
	}
	
	/**
	 * Function - Get Password Hash
	 * @param mixed $password Password
	 * @return mixed
	 */
	protected static function getPasswordHash($password) {
		return $password;
	}
	
	/**
	 * Function - Generate New Token
	 * @param mixed $length=128 Length=128
	 */
	protected static function generateNewToken($length=128) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!*[]';
	    $str = '';
	    for($i=0;$i<$length;$i++){
	        $str .= $characters[mt_rand(0, strlen($characters)-1)];
	    }
	    return $str;
	}
	
	/**
	 * Function - Login
	 * @param mixed $email Email
	 * @param mixed $password Password
	 * @param mixed $expires=0 Expires=0
	 * @return mixed
	 */
	public static function login($email,$password,$expires=0) {
		return false;
	}
	
	/**
	 * Function - Logout
	 */
	public static function logout() {
		self::$user = null;
	}
	
	/**
	 * Function - Authenticate
	 * @return mixed
	 */
	public static function authenticate() {
		if(self::isSession()) {
			return true;
		} else {
			//attempt to authenticate
		}
		return false;
	}
	
	/**
	 * Function - Is Admin
	 * @return mixed
	 */
	public static function isAdmin() {
		return false;
	}
	
	/**
	 * Function - Is Super Admin
	 * @return mixed
	 */
	public static function isSuperAdmin() {
		return false;
	}
	
}