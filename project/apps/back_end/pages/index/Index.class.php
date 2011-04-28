<?php
namespace project\apps\back_end\pages\index;

/**
 * Index class 
 * 
 * Index
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage index
 */
class Index extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.home','Admin - Home Page'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		$blockMenu = new \project\apps\back_end\widgets\menu\Menu();
		$blockMenu->setListType('blocks');
		$this->blockMenu = $blockMenu->getRenderedWidget();
		
		return 'project/apps/back_end/pages/index/views/index.twig';
	}

}