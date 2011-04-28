<?php
namespace project\apps\{{ projectName }}\templates;

/**
 * PageTemplate class 
 * 
 * PageTemplate
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
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
		$this->headerWidget->addStyleSheet('/css/globals/default.css');
		$this->headerWidget->addStyleSheet('/css/globals/colorbox-default.css');
		$this->headerWidget->addJavaScript('/js/globals/jquery.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery-ui.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery.colorbox-min.js');
		$this->headerWidget->addJavaScript('/js/globals/core.js');
	}
	
	/**
	 * Function - Post Execute
	 */
	protected function postExecute(\nano\core\routing\Routing $routing) {
		$this->header = $this->headerWidget->getRenderedWidget();
	}
}