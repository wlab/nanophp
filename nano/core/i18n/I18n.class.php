<?php
namespace nano\core\i18n;

/**
 * I18n library.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.i18n
 */
class I18n {
	private static $instance = null;
	private $languageCode = null;
	private $instanceDataStore = null;
	
 	/**
	 * Function - __construct
	 */
	final private function __construct() {
		$this->instanceDataStore = array();
	}
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Returns the singleton instance of the I18n class.
	 * @return \I18n Class instance.
	 */
	public static function getInstance() {
		if(self::$instance==null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Set the language to be used for this page.
	 * The specified $code must be in the form L_R, where 'L' is a two- or three-letter language tag,
	 * comprised of lower case letters only, and 'R' is a two- or three-letter region tag,
	 * comprised of upper case letters only.
	 * 
	 * @param string $code Language code as described above.
	 */
	public function setLanguage($code) {
		if(preg_match('/^[a-z]{2,3}\_[A-Z]{2,3}$/',$code)){
			$this->languageCode = $code;
		} else {
			///TODO:APC: Why does this not throw an I18nException?
			throw new \nano\core\exception\CoreException('Could not set language from code \''.$code.'\'');
		}
	}
	
	/**
	 * Get thge language currently in use.
	 * @return string Language code.
	 */
	public function getLanguage() {
		return $this->languageCode;
	}
	
	/**
	 * Function - Variable Replacement
	 * @param mixed $str Str
	 * @param mixed $replacements=array() Replacements=array()
	 */
	private function variableReplacement($str,$replacements=array()){
		foreach($replacements as $key => $value){
			$str = str_replace($key,$value,$str);
		}
		return $str;
	}
	
	/**
	 * Get an internationalised string in the currently selected language.
	 * If a string for the specified key and current language is not found in the language table,
	 * $defaultValue will be returned instead.
	 * $replacements is an optional associative array of strings to be replaced in the output.
	 * For example, if $replacements['foo']=='bar', all instances of 'foo' in the output will
	 * be replaced with 'bar' at the last stage of the function before returning. Replacements will
	 * NOT be committed to either the cache or the database.
	 * 
	 * Config options: i18n_marker, i18n_overwrite, i18n_populate.
	 * 
	 * @param string $key String key.
	 * @param string $defaultValue
	 * @param array $replacements
	 * @return string
	 */
	public function get($key,$defaultValue='',$replacements=array()) {
		
		//String key must not be empty.
		if(($key===null)||($key=='')){
			throw new \nano\core\exception\I18nException('Key must not be empty');
		}
		
		//Check that language code has been set.
		if($this->languageCode==null){
			throw new \nano\core\exception\I18nException('Language code must be set');
		}
		
		$populate = (\nano\core\config\Config::get('i18n_populate')==true)? true : false;
		$overWrite = (\nano\core\config\Config::get('i18n_overwrite')==true)? true : false;
		
		//Attempt to retrieve the required value from the data store in this instance.
		$value = isset($this->instanceDataStore[$key])? $this->instanceDataStore[$key] : null;
		
		//If the value is found, no need to proceed unless in overwrite mode.
		if($value===null || $overWrite){
			//Flag specifying whether Memecache should be updated with a new value for this key.
			$cacheUpdate = false;
			$cacheKey = 'nanophp-i18n-'.$this->languageCode.'-'.md5(\nano\core\routing\Routing::getInstance()->getUrl());
			$memcacheDataStore = array();
			//If caching is disabled in the project configuration, skip this part...
			if(\nano\core\config\Config::get('caching')==true){
				//If the value has not been found in the local data store, try to fetch from Memcache based on page URL.
				if($value===null){
					$memcacheDataStore = unserialize(\nano\core\cache\Memcached::getInstance()->get($cacheKey));
					$memcacheDataStore = is_array($memcacheDataStore)? $memcacheDataStore : array();
					$value = isset($memcacheDataStore[$key])? $memcacheDataStore[$key] : null;
					if($value!==null){
						//Value has been found in Memcache. Merge all memcache data into the local data store.
						///TODO:APC: Why set $cacheUpdate here? It seems like a bit of a hack...
						$cacheUpdate = true;
						$this->instanceDataStore = array_merge($this->instanceDataStore,$memcacheDataStore);
						//As value has been found in the cache, it must exist in the database, so just return unless in overwrite mode.
						if(!$overWrite){
							if(\nano\core\config\Config::get('i18n_marker')==true){
								return '{{ '.$this->variableReplacement($value,$replacements).' }}';
							}
							return $this->variableReplacement($value,$replacements);
						}
					}
				}
			}
		
			//If the value has still not been found, or populate or overwrite options are set, we need to refer to the database.
			if($value===null || $populate || $overWrite){
				$databases = \nano\core\config\Config::get('databases');
				$defaultDatabase = $databases['default']['name'];
				$language = \nano\core\db\ORM::getInstance()->getTable('Language',$defaultDatabase)->retrieveByPk($this->languageCode,$key);
				if($language==null || $overWrite){
					//Key was not found in the database. Use the default value.
					if($populate || $overWrite){
						foreach(\project\config\Config::$language as $supportedLanguageCode => $supportedLanguageConfig){
							try {
								$language = \nano\core\db\ORM::getInstance()->getTable('Language',$defaultDatabase)->retrieveByPk($supportedLanguageCode,$key);
								if($language==null){
									$languageModelClassName = 'project\db\om\\'.$defaultDatabase.'\Language';
									$language = new $languageModelClassName();
									$language->code = $supportedLanguageCode;
									$language->key = $key;
									$language->value = $defaultValue;
									$language->save();
								} elseif($overWrite) {
									$language->value = $defaultValue;
									$language->save();
								}
								$cacheUpdate = true;
								$this->instanceDataStore[$language->key] = $language->value;
							} catch (\Exception $e) {
								//Error automatically logged and displayed.
							}
						}

					}
				} else {
					//Key was found in the database. Add the value to the local store, and mark cache to be updated.
					$cacheUpdate = true;
					$this->instanceDataStore[$language->key] = $language->value;
				}
			}
		
			if($cacheUpdate){
				if(\nano\core\config\Config::get('caching')==true){
					if((!empty($this->instanceDataStore)) && ($memcacheDataStore!=$this->instanceDataStore)) {
						\nano\core\cache\Memcached::getInstance()->set($cacheKey,serialize($this->instanceDataStore),\nano\core\cache\Memcached::EXPIRES_30MINS);
					}
				}
			}
		
		}
		
		if(\nano\core\config\Config::get('i18n_marker')==true){
			return isset($this->instanceDataStore[$key])? '{{ '.$this->variableReplacement($this->instanceDataStore[$key],$replacements).' }}' : '{{ }}';
		}
		
		return isset($this->instanceDataStore[$key])? $this->variableReplacement($this->instanceDataStore[$key],$replacements) : null;
	}
}