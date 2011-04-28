<?php
namespace project\apps\{{ projectName }}\widgets\header;

/**
 * Header class 
 * 
 * Header
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package widgets
 * @subpackage header
 */
class Header extends \nano\core\widget\Widget {

	/**
	 * Function - Add Style Sheet File
	 * @param $path Path To Style Sheet File
	 */
	public function addStyleSheet($path) {
		$this->styleSheets[] = $path;
	}
	
	/**
	 * Function - Add JavaScript File
	 * @param $path Path To JavaScript File
	 */
	public function addJavaScript($path) {
		$this->javaScripts[] = $path;
	}
	
	/**
	 * Function - Set Title
	 * @param $title Page Title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Function - Pre Load
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	protected function preLoad(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		$this->styleSheets = array();
		$this->javaScripts = array();
		$this->title = '';
	}

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 * @return Template Location
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		return 'project/apps/{{ projectName }}/widgets/header/views/header.php';
	}
	
}