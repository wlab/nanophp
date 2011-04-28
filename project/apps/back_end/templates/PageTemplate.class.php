<?php
namespace project\apps\back_end\templates;

/**
 * PageTemplate class 
 * 
 * Page Template
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package back_end
 * @subpackage templates
 */
class PageTemplate extends \nano\core\page\Page {
	
	protected $headerWidget = null;
	
	/**
	 * Function - Pre Load
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function preLoad(\nano\core\routing\Routing $routing){
		if(!\project\session\Session::isAdmin()){
			$routing->getResponse()->pageRedirect('/login');
		}
	}
	
	/**
	 * Function - Pre Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function preExecute(\nano\core\routing\Routing $routing) {
		$this->headerWidget = new \project\apps\back_end\widgets\header\Header();
		$this->headerWidget->addStyleSheet('/css/globals/admin.css');
		$this->headerWidget->addStyleSheet('/css/globals/default.css');
		$this->headerWidget->addStyleSheet('/css/globals/jquery/plugins/ui/'.\nano\core\config\Config::get('jquery_theme').'/jquery-ui-1.8.5.custom.css');
		$this->headerWidget->addStyleSheet('/css/globals/jquery/plugins/colorbox/colorbox-default.css');
		$this->headerWidget->addJavaScript('/js/globals/jquery.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery-ui.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery.colorbox-min.js');
		$this->headerWidget->addJavaScript('/js/globals/core.js');
		$this->headerWidget->addJavaScript('/js/globals/admin.js');
	}
	
	/**
	 * Function - Post Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function postExecute(\nano\core\routing\Routing $routing) {
		$this->header = $this->headerWidget->getRenderedWidget();
	}
}