<?php
namespace project\globals\pages\page_not_found;

class PageNotFound extends \project\globals\templates\PageTemplate {
	
	public function execute(\nano\core\routing\Routing $routing){
		header("HTTP/1.0 404 Not Found");
		$this->url = \nano\core\routing\Routing::getInstance()->getUrl();
		return 'project/globals/pages/page_not_found/views/page_not_found.twig';
	}
}
