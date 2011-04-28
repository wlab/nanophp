<?php
namespace project\db\om\nanophp;

/**
 * Language class 
 * 
 * Language
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package om
 * @subpackage nanophp
 */
class Language extends \project\db\om\nanophp\base\BaseLanguage {

	/**
	 * Function - Validate Code
	 * @param mixed $value Value
	 */
	public static function validateCode($value){
		if(preg_match('/^[a-z]{2,3}\_[A-Z]{2,3}$/',$value)){
			return true;
		}
		return false;
	}
	
	/**
	 * Function - Save
	 */
	public function save(){
		if(\nano\core\config\Config::get('caching')==true){
			$memcached = \nano\core\cache\Memcached::getInstance();
			$memcached->flush();
		}
		parent::save();
	}

}