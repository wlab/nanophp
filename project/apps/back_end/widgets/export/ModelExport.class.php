<?php
namespace project\apps\back_end\widgets\export;

/**
 * ModelExport class 
 * 
 * Model Export
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package widgets
 * @subpackage export
 */
class ModelExport extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$this->fieldInformation = $modelTable->getFieldInformation();
			$this->fieldNames = array_keys($this->fieldInformation);
			
			$this->database = $_GET['database'];
			$this->model = $_GET['model'];
			
			if($routing->isAjax()){
				$query = new \nano\core\db\core\SelectQuery();
				$query->from($modelTable->getTableName())->where('1');
				$this->results = $modelTable->doSelect($query);
				return 'project/apps/back_end/widgets/export/views/model_export_'.$_GET['type'].'.php';
			}
		}
		
		return 'project/apps/back_end/widgets/export/views/model_export.twig';
	
	}

}