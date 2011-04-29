<?php
namespace nano\core\web;

/**
 * Response class.
 * The singleton instance provides functions to control certain elements of the server's response.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.web
 */
class Response {
	
	private static $instance = null;
	
	private $onceOnlyCookies = array();
	private $cookies = array();
	
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	
	/**
	 * Get the singleton instance of the Response class.
	 * @return Response Instance.
	 */
	public static function getInstance() {
		if(self::$instance===null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Expire once only cookies.
	 * Cookies are expired by setting their value to '' and the expiry time to a date in the past.
	 * @param bool $saveBeforeExpire=true Save Before Expire=true
	 */
	public function expireOnceOnlyCookies($saveBeforeExpire=true){
		if(!empty($_COOKIE)){
			foreach($_COOKIE as $key => $value){
				if(preg_match('/^nano\_ooc\_/',$key)){
					if($saveBeforeExpire){
						$this->onceOnlyCookies[$key] = $value;
					}
					//expire only once cookies
					$this->setCookie($key,'',time()-3600,'/');
				}
			}
		}
	}
	
	/**
	 * Set a once only cookie.
	 * @param string $name Name of the cookie. Note that for transmission, 'nano_ooc_' will automatically be prepended.
	 * @param mixed $value Value of the cookie. This may be any variable/object, and will be serialized for transmission.
	 */
	public function setOnceOnlyCookie($name,$value='') {
		$this->onceOnlyCookies['nano_ooc_'.$name] = htmlentities(serialize($value),ENT_QUOTES,'UTF-8');
	}
	
	/**
	 * Set a cookie to be sent in the HTTP headers.
	 * The format of arguments and their default values are identical to the PHP inbuilt function
	 * of the same name, with the exception that the default $path is '/', i.e. the whole domain.
	 * See {@link http://php.net/manual/en/function.setcookie.php } for a more detailed description of the parameters.
	 * @param string $name The name of the cookie.
	 * @param string $value The value of the cookie.
	 * @param int $expire Expiry time, as a UNIX timestamp. If 0, the cookie will expire at the end of the session.
	 * @param string $path The path on the server in which the cookie will be available on.
	 * @param string $domain The domain that the cookie is available to.
	 * @param bool $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
	 * @param bool $httponly When TRUE the cookie will be made accessible only through the HTTP protocol, not via client-side scripts.
	 */
	public function setCookie($name, $value='', $expire=0, $path='/', $domain=null, $secure=false, $httponly=false) {
		$this->cookies[$name] = array(
			'value' => $value,
			'expire' => $expire,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httponly' => $httponly,
		);
		setcookie($name,$value,$expire,$path,$domain,$secure,$httponly);
	}
	
	/**
	 * Set the HTTP status code and protocol version to be transmitted with the response.
	 * @param int $code HTTP status code. Default 200 (OK).
	 * @param string $version HTTP protocol version. Default 1.0. 
	 */
	public function setHttpStatus($code=200,$version='1.0') {
		header('HTTP/'.$version.' '.$code);
		$this->setHttpHeader('Status',$code);
	}
	
	/**
	 * Set an HTTP header.
	 * @param string $name Name of the header.
	 * @param string $value Value to assign.
	 */
	public function setHttpHeader($name, $value) {
		header($name.': '.$value);
	}
	
	/**
	 * Redirect by setting the HTTP Location header.
	 * The HTTP response code will also be set to '302 Found' unless it has already been modified.
	 * @param mixed $url URL to redirect to. If null or omitted, the current URL will be used.
	 */
	public function pageRedirect($url=null) {
		$url = ($url==null)? $_SERVER['REQUEST_URI'] : $url;
		ob_start();
		$this->expireOnceOnlyCookies(false);
		foreach($this->onceOnlyCookies as $key => $value){
			setcookie($key,$value,0,'/');
		}
		foreach($this->cookies as $name => $params){
			setcookie($name,$params['value'],$params['expire'],$params['path'],$params['domain'],$params['secure'],$params['httponly']);
		}
		header('Location: '.$url);
		ob_flush();
		//nothing should be allowed to run after this command
		exit;
	}
	
}