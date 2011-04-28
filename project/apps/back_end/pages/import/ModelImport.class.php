<?php
namespace project\apps\back_end\pages\import;

/**
 * ModelImport class 
 * 
 * Model Import
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage import
 */
class ModelImport extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing){
		$this->headerWidget->setTitle(i18n('admin.pagetitle.model-import','Admin - Import'));
		
		$menu = new \project\apps\back_end\widgets\menu\Menu();
		$this->menu = $menu->getRenderedWidget();
		
		$modelImport = new \project\apps\back_end\widgets\import\ModelImport();
		$this->modelImport = $modelImport->getRenderedWidget();
		
		return 'project/apps/back_end/pages/import/views/model_import.twig';
	}

}