<?php
namespace project\globals\templates;

class PageTemplate extends \nano\core\page\Page {
	
	protected $headerWidget = null;
	
	protected function preExecute(\nano\core\routing\Routing $routing) {
		$this->headerWidget = new \project\globals\widgets\header\Header();
		$this->headerWidget->addStyleSheet('/css/globals/admin.css');
		$this->headerWidget->addStyleSheet('/css/globals/default.css');
		$this->headerWidget->addStyleSheet('/css/globals/jquery/plugins/ui/'.\nano\core\config\Config::get('jquery_theme').'/jquery-ui-1.8.5.custom.css');
		$this->headerWidget->addStyleSheet('/css/globals/jquery/plugins/colorbox/colorbox-default.css');
		$this->headerWidget->addJavaScript('/js/globals/jquery-1.4.2.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery-ui-1.8.5.custom.min.js');
		$this->headerWidget->addJavaScript('/js/globals/jquery.colorbox-min.js');
		$this->headerWidget->addJavaScript('/js/globals/core.js');
		$this->headerWidget->addJavaScript('/js/globals/admin.js');
	}
	
	protected function postExecute(\nano\core\routing\Routing $routing) {
		$this->header = $this->headerWidget->getRenderedWidget();
	}
}