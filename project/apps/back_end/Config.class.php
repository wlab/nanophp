<?php
namespace project\apps\back_end;

/**
 * Config class 
 * 
 * Config
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package projects
 * @subpackage back_end
 */
class Config extends \project\config\Config {
	
	/**
	 * Function - Boot Strap
	 */
	public static function bootStrap(){
		/*
		Put any boot strap functions in here... They will get called prior to the routing loading any classes
		\nano\core\cache\Memcached::getInstance()->setKeyPrefix('back_end');
		*/
		\nano\core\i18n\I18n::getInstance()->setLanguage('en_GB');
	}
	
}