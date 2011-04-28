<?php
namespace project\apps\back_end\pages\edit;

/**
 * Edit class 
 * 
 * Edit
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage edit
 */
class Edit extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.edit','Admin - Edit Existing Record'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		if($routing->getRequest()->getOnceOnlyCookie('edit-form-record-preview')!==null){
			$this->previewUrl = '/ajax/'.$_GET['database'].'/'.$_GET['model'].'/view?ids='.$_GET['ids'];
		}
		
		$edit = new \project\apps\back_end\widgets\edit\Edit();
		$this->edit = $edit->getRenderedWidget();
		
		return 'project/apps/back_end/pages/edit/views/edit.twig';
	}
	
}