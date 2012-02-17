<?php
namespace project\globals\widgets\header;

/**
 * Header class 
 * 
 * Header
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package widgets
 * @subpackage header
 */
class Header extends \nano\core\widget\Widget {

	/**
	 * Function - Add Style Sheet
	 * @param mixed $path Path
	 */
	public function addStyleSheet($path) {
		$this->styleSheets[] = $path;
	}
	
	/**
	 * Function - Add Java Script
	 * @param mixed $path Path
	 */
	public function addJavaScript($path) {
		$this->javaScripts[] = $path;
	}
	
	/**
	 * Function - Set Title
	 * @param mixed $title Title
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
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		return 'project/globals/widgets/header/views/header.php';
	}
	
}