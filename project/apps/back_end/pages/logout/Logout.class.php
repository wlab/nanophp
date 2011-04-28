<?php
namespace project\apps\back_end\pages\logout;

/**
 * Logout class 
 * 
 * Logout
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package pages
 * @subpackage logout
 */
class Logout extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Pre Load
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function preLoad(\nano\core\routing\Routing $routing){
		//over-ride parent
	}
	
	/**
	 * Function - Pre Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function preExecute(\nano\core\routing\Routing $routing){
		//over-ride parent
	}
	
	/**
	 * Function - Post Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function postExecute(\nano\core\routing\Routing $routing){
		//over-ride parent
	}

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing){
		\project\session\Session::logout();
		return null;
	}
	
}