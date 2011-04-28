<?php
namespace project\apps\back_end\pages\list_view;

/**
 * ListView class 
 * 
 * List View
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage list_view
 */
class ListView extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.list','Admin - List Records'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		$filter = new \project\apps\back_end\widgets\filter\Filter();
		$this->filter = $filter->getRenderedWidget();
		
		$displayList = new \project\apps\back_end\widgets\display_list\DisplayList();
		$this->displayList = $displayList->getRenderedWidget();
		
		if($routing->getRequest()->getOnceOnlyCookie('edit-form-record-deleted')!==null){
			$this->successMessage = 'Record has been successfully deleted';
		}
		
		return 'project/apps/back_end/pages/list_view/views/list_view.twig';
	}
	
}