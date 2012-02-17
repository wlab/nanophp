<?php
namespace project\apps\example\pages\index;

/**
 * Index class 
 * 
 * Index
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package pages
 * @subpackage index
 */
class Index extends \project\apps\example\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle('Example Index Page');
		return 'project/apps/example/pages/index/views/index.twig';
	}
}