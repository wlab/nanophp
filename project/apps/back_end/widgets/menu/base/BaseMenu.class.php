<?php
namespace project\apps\back_end\widgets\menu\base;

/**
 * BaseMenu class 
 * 
 * Base Menu
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package menu
 * @subpackage base
 */
class BaseMenu extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Set List Type
	 * @param mixed $listType List Type
	 */
	public function setListType($listType){
		$this->listType = $listType;
	}

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->isSuperAdmin = \project\session\Session::isSuperAdmin();
		
		$databases = array_keys(\nano\core\helpers\FileSystem::getFoldersIn($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'));
		foreach($databases as $database){
			$mapFile = 'project\db\om\\'.$database.'\map\Map';
			$models = $mapFile::$modelNameToTableName;
			foreach($models as $modelName => $modelNiceName){
				$models[$modelName] = preg_replace('/([a-z]{1})([A-Z]{1})/','$1 $2',$modelName).'s';
				$models[$modelName] = preg_replace('/ys$/','ies',$models[$modelName]);
			}
			$this->menu[$database] = $models;
		}
		if($this->listType=='blocks'){
			return 'project/apps/back_end/widgets/menu/views/menu_blocks.twig';
		}
		return 'project/apps/back_end/widgets/menu/views/menu.twig';
	}
	
}