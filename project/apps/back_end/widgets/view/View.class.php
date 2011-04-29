<?php
namespace project\apps\back_end\widgets\view;

/**
 * View class 
 * 
 * View
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package widgets
 * @subpackage view
 */
class View extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			$fieldNames = array_keys($fieldInformation);
			$jsonObj = json_decode(base64_decode(rawurldecode($_GET['ids'])));
			$result = null;
			
			try{
				$query = new \nano\core\db\core\SelectQuery();
				$query->from($modelTable->getTableName())->where($this->getWhere($jsonObj,$fieldNames).' LIMIT 1');
				$results = $modelTable->doSelect($query);
				$result = isset($results[0])? $results[0] : null;
			} catch (\Exception $e) {
				//just log
			}
			
			$this->displayItems = array();
			foreach($fieldNames as $fieldName){
				
				if($fieldInformation[$fieldName]['is_foreign_reference']){
					$value = $result[$modelTable->getModelName()]->getValueFromKey($fieldInformation[$fieldName]['use_model']);
				} else {
					$value = $result[$modelTable->getModelName()]->getValueFromKey($fieldName);
					//$value = $result[$modelTable->getModelName()]->{$fieldInformation[$fieldName]['get_function']}();
				}
				
				$this->displayItems[$fieldName] = array(
					'nice_name' => i18n('admin.label-for-field.'.$_GET['database'].'.'.$_GET['model'].'.'.$fieldName,$this->getLabel($fieldName)),
					'value' => $value
				);
			}
			$this->databaseName = $_GET['database'];
			$this->modelName = $_GET['model'];
		}
		
		return 'project/apps/back_end/widgets/view/views/view.twig';
	}
	
	/**
	 * Function - Get Where
	 * @param mixed $dataObj Data Obj
	 * @param mixed $fieldNames Field Names
	 */
	protected function getWhere($dataObj,$fieldNames){
		$where = '';
		foreach($dataObj as $key => $value){
			if($value!=''){
				if(in_array($key,$fieldNames)){
					$where .= '`'.$key.'` = "'.addslashes($value).'" AND ';
				}
			}
		}
		$where = preg_replace('/\s{1}AND\s{1}$/','',$where);
		return ($where=='')? '1' : $where;
	}
	
	/**
	 * Function - Get Label
	 * @param mixed $key Key
	 */
	protected function getLabel($key){
		$key = preg_replace('/(.*?)\.(.+)$/','$2',$key);
		$key = preg_replace('/([a-z]{1})([A-Z]{1})/','$1 $2',$key);
		$key = preg_replace('/\_([a-z]{1})/ie',"' '.strtoupper('\\1')",$key);
		return ucfirst($key);
	}
	
}