<?php
namespace project\apps\back_end\templates;

/**
 * WidgetTemplate class 
 * 
 * Widget Template
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package back_end
 * @subpackage templates
 */
class WidgetTemplate extends \nano\core\widget\Widget {
	
	/**
	 * Function - Pre Load
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	protected function preLoad(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		if(!\project\session\Session::isAdmin()){
			$routing->getResponse()->pageRedirect('/login');
		}
	}
	
}