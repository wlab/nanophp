<?php
namespace nano\core\view;

/**
 * Twiglet class 
 * 
 * Twiglet
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.view
 */
class Twiglet {
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Function - Get Obj
	 * @param mixed $objs Objs
	 * @param mixed $key Key
	 * @return mixed
	 */
	public static function getObj($objs,$key){
		return isset($objs[$key])? $objs[$key] : '{{ '.$key.' }}';
	}
	
	/**
	 * Function - Load
	 * @param mixed $path Path
	 * @param mixed $objs=array() Objs=array()
	 * @param  $return=false Return=false
	 */
	public static function load($path,$objs=array(), $return=false) {
		$view = preg_replace('/\{\{\s*([a-zA-Z0-9\_]+)\s*\}\}/e','self::getObj($objs,\'$1\')', file_get_contents($path));
		if($return){
			return $view;
		}
		echo $view;
	}
}