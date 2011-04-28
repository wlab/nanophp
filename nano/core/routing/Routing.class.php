<?php
namespace nano\core\routing;

/**
 * Routing class 
 * 
 * Routing
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package core
 * @subpackage routing
 */
class Routing {
	private static $instance = null;
	
	private $url = null;
	private $protocol = null;
	private $domain = null;
	private $path = null;
	private $pathPrefix = null;
	private $isAjax = false;
	private $customConfigClassName = null;
	private $customRoutingClassName = null;
	private $routeKey = null;
	private $request = null;
	private $response = null;
	
 	/**
	 * Function - __construct
	 */
	final private function __construct() {
		$this->customConfigClassName = 'project\apps\\'.$_SERVER['PROJECT_APP'].'\Config';
		$this->customRoutingClassName = 'project\apps\\'.$_SERVER['PROJECT_APP'].'\Routing';
	}
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Function - Get Instance
	 */
	public static function getInstance() {
		if(self::$instance==null){
			self::$instance = new self();
			self::$instance->url = self::$instance->getIncomingUrl();
			self::$instance->parseUrl();
			self::$instance->request = \nano\core\web\Request::getInstance();
			self::$instance->response = \nano\core\web\Response::getInstance();
			self::$instance->response->expireOnceOnlyCookies();
			//configure any installed libraries to environmental settings
			\nano\core\autoloader\Autoloader::configure();
			//configure using project specific bootstrap method
			$configClassName = self::$instance->customConfigClassName;
			$configClassName::bootStrap();
			self::$instance->setLanguage();
			//load the page which matches the routing profile
			if(!self::$instance->load()){
				new \project\globals\pages\page_not_found\PageNotFound();
			}
		}
		return self::$instance;
	}
	
	/**
	 * Function - Set Language
	 * @param mixed $code=null Code=null
	 */
	private function setLanguage($code=null) {
		if($code==null){
			//attempt to set it from the browser
			if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
				if(preg_match('/([a-z]{2})[\_\-]{1}([a-z]{2,3})/i',$_SERVER['HTTP_ACCEPT_LANGUAGE'],$matches)){
					$code = $matches[1].'_'.$matches[2];
				}
			}
			if(isset($_COOKIE['nano_language'])){
				$code = $_COOKIE['nano_language'];
			}
		}
		if($code!=null){
			$configClassName = $this->customConfigClassName;
			$configLanguages = $configClassName::$language;
			foreach($configLanguages as $supportedLanguageCode => $supportedLanguageConfig){
				if(preg_match('/'.$supportedLanguageCode.'/i',$code)){
					\nano\core\i18n\I18n::getInstance()->setLanguage($supportedLanguageCode);
				}
			}
		}
	}
	
	/**
	 * Function - Load
	 */
	private function load() {
		$routingClassName = $this->customRoutingClassName;
		$routing = $routingClassName::$routing;
		foreach($routing as $key => $route){
			$isLoadable = true;
			if(isset($route['domain_prefix'])){
				$configClassName = $this->customConfigClassName;
				$environmentDomain = $configClassName::$environments[\nano\core\config\Config::getEnvironment()];
				$environmentDomain = preg_replace('/(\.)|(\-)/', '\\\$1', $environmentDomain);
				if(!preg_match('/'.$route['domain_prefix'].$environmentDomain.'$/', $this->domain)){
					$isLoadable = false;
				}
			}
			$url = $route['url'];
			$requirements = isset($route['requirements']) ? $route['requirements'] : array();
			$class = $route['class'];
			if($this->matches($url, $requirements) && $isLoadable){
				$this->routeKey = $key;
				if(isset($route['try_class'])){
					$route['try_class'] = preg_replace('/\{\{\s?(.+?)\s?\}\}/e','$_GET[\'\1\']',$route['try_class']);
					if(\nano\core\autoloader\Autoloader::isFile($route['try_class'])){
						$class = $route['try_class'];
					}
				}
				if(isset($route['get_inject'])){
					foreach($route['get_inject'] as $key => $value){
						$_GET[$key] = $value;
					}
				}
				if(isset($route['post_inject'])){
					foreach($route['post_inject'] as $key => $value){
						$_POST[$key] = $value;
					}
				}
				if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
					$this->isAjax = true;
				}
				new $class();
				return true;
			}
		}
		return false;
	}

	/**
	 * Function - Get Incoming Url
	 */
	private function getIncomingUrl() {
		$url = 'http';
		$_SERVER['HTTPS'] = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : '';
		if($_SERVER['HTTPS']=='on'){
			$url .= 's';
		}
		$url .= '://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		return $url;
	}
	
	/**
	 * Function - Matches
	 * @param mixed $pattern Pattern
	 * @param mixed $requirements=array() Requirements=array()
	 */
	private function matches($pattern,$requirements=array()) {
		//replace slug with named regex
		$pattern = preg_replace('/\//','\/',$pattern);
		$pattern = preg_replace('/\/\:([\w]+)/','/(?<\1>[^\/]+)',$pattern);
		$pattern = '/^(?<language>\/[a-z]{2,3}\_[A-Z]{2,3})?(?<ajax>\/ajax)?'.$pattern.'$/';
		if(preg_match($pattern,$this->path,$matches)){
			$this->pathPrefix = '';
			$matches['language'] = isset($matches['language'])? $matches['language'] : null;
			if($matches['language']!=null){
				$this->pathPrefix .= $matches['language'];
				$isoLang = substr($matches['language'],1);
				$this->setLanguage($isoLang);
				setcookie('nano_language',$isoLang,0,'/');
			}
			$matches['ajax'] = isset($matches['ajax'])? $matches['ajax'] : '';
			if($matches['ajax']=='/ajax'){
				$this->pathPrefix .= '/ajax';
				$this->isAjax = true;
			}
			unset($matches['ajax']); //remove ajax from get var as not needed
			unset($matches['language']); //remove language from get var as not needed
			unset($matches[0]); //remove first match
			foreach($matches as $key => $match){
				if(intval($key)==0){
					if(isset($requirements[$key])){
						if(!preg_match('/^'.$requirements[$key].'$/',$match)){
							return false;
						}
					}
					$_GET[$key] = $match;
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Function - Parse Url
	 */
	private function parseUrl() {
		if(preg_match('/^(?<protocol>([\d\w]+)):\/\/(?<domain>[^\/]+)[\/]?+(?<path>[^\?\&\#]+)?+(?<args>.*?)$/i',$this->url,$matches)){
			$this->protocol = $matches['protocol'];
			$this->domain = $matches['domain'];
			$this->path = '/'.$matches['path'];
		}
	}
	
	/**
	 * Function - Is Ajax
	 * @return mixed
	 */
	public function isAjax() {
		return $this->isAjax;
	}
	
	/**
	 * Function - Get Url
	 * @return mixed
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * Function - Get Url For Param Replacement
	 * @param mixed $paramKey Param Key
	 * @param mixed $params Params
	 * @return mixed
	 */
	public static function getUrlForParamReplacement($paramKey,$params){
		if(array_key_exists($paramKey,$params)){
			return '/'.$params[$paramKey];
		}
		return '';
	}
	
	/**
	 * Function - Get Url For
	 * @param mixed $routeKey Route Key
	 * @param mixed $params=array() Params=array()
	 * @param mixed $absolute=false Absolute=false
	 * @return mixed
	 */
	public function getUrlFor($routeKey,$params=array(),$absolute=false) {
		$routingClassName = $this->customRoutingClassName;
		if(isset($routingClassName::$routing[$routeKey])){
			$url = isset($routingClassName::$routing[$routeKey]['url_for'])? $routingClassName::$routing[$routeKey]['url_for'] : $routingClassName::$routing[$routeKey]['url'];
			$url = preg_replace('/\/:([^\/]+)/e','self::getUrlForParamReplacement(\'\\1\',$params)',$url);
			if($absolute){
				return $this->protocol.'://'.$this->domain.$url;
			}
			return $url;
		}
		return null;
	}
	
	/**
	 * Function - Get Protocol
	 * @return mixed
	 */
	public function getProtocol() {
		return $this->protocol;
	}
	
	/**
	 * Function - Get Domain
	 * @return mixed
	 */
	public function getDomain() {
		return $this->domain;
	}
	
	/**
	 * Function - Get Path
	 * @return mixed
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Function - Get Path Prefix
	 * @return mixed
	 */
	public function getPathPrefix() {
		return $this->pathPrefix;
	}
	
	/**
	 * Function - Get Route Key
	 * @return mixed
	 */
	public function getRouteKey() {
		return $this->routeKey;
	}
	
	/**
	 * Function - Get Request Object
	 * @return \nano\core\web\Request
	 */
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * Function - Get Response Object
	 * @return \nano\core\web\Response
	 */
	public function getResponse() {
		return $this->response;
	}
	
}