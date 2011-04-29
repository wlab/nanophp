<?php
namespace project\apps\{{ projectName }}\templates;

/**
 * PageTemplate class 
 * 
 * PageTemplate
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package {{ projectName }}
 * @subpackage templates
 */
class PageTemplate extends \nano\core\page\Page {
	
	protected $headerWidget = null;
	
	/**
	 * Function - Pre Execute
	 */
	protected function preExecute(\nano\core\routing\Routing $routing) {
		$this->headerWidget = new \project\apps\{{ projectName }}\widgets\header\Header();
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
	 */
	protected function postExecute(\nano\core\routing\Routing $routing) {
		$this->header = $this->headerWidget->getRenderedWidget();
	}
}