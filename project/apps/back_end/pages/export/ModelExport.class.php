<?php
namespace project\apps\back_end\pages\export;

/**
 * ModelExport class 
 * 
 * Model Export
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package pages
 * @subpackage export
 */
class ModelExport extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.model-export','Admin - Export'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		$modelExport = new \project\apps\back_end\widgets\export\ModelExport();
		$this->modelExport = $modelExport->getRenderedWidget();
		
		return 'project/apps/back_end/pages/export/views/model_export.twig';
	}

}