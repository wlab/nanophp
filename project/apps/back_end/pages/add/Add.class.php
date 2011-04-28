<?php
namespace project\apps\back_end\pages\add;

/**
 * Add class 
 * 
 * Add
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage add
 */
class Add extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.add','Admin - Add New Record'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		if($routing->getRequest()->getOnceOnlyCookie('add-form-record-preview')!==null){
			$this->previewUrl = '/ajax/'.$_GET['database'].'/'.$_GET['model'].'/view?ids='.$routing->getRequest()->getOnceOnlyCookie('add-form-record-preview');
		}
		
		$add = new \project\apps\back_end\widgets\add\Add();
		$this->add = $add->getRenderedWidget();
		
		return 'project/apps/back_end/pages/add/views/add.twig';
	}

}