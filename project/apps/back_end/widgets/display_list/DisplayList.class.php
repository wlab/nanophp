<?php
namespace project\apps\back_end\widgets\display_list;

/**
 * DisplayList class 
 * 
 * Display List
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package widgets
 * @subpackage display_list
 */
class DisplayList extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->displayListErrors = array();
		$this->limit = 20;
		$this->showNext = false;
		$this->showPrevious = false;
		$this->previousPage = null;
		$this->nextPage = null;
		$this->currentPage = isset($_POST['page'])? $_POST['page'] : 1;
		$this->orderByField = isset($_POST['orderby']['field'])? $_POST['orderby']['field'] : '';
		$this->orderByOrder = isset($_POST['orderby']['order'])? $_POST['orderby']['order'] : '';
		if($this->currentPage > 1){
			$this->showPrevious = true;
			$this->previousPage = $this->currentPage - 1;
		}
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			
			$fieldNames = array_keys($fieldInformation);
			foreach($fieldNames as $fieldName){
				$this->fieldNames[$fieldName] = i18n('admin.label-for-field.'.$_GET['database'].'.'.$_GET['model'].'.'.$fieldName,$this->getLabel($fieldName));
			}
			
			$orderBy = '';
			if($this->orderByField!=''){
				if(in_array($this->orderByField,$fieldNames)){
					if($this->orderByOrder=='asc'||$this->orderByOrder=='desc'){
						$orderBy = 'ORDER BY `'.$this->orderByField.'` '.strtoupper($this->orderByOrder).' ';
					}
				}
			}
			
			$this->displayItems = array();
			
			$results = array();
			try{
				$query = new \nano\core\db\core\SelectQuery();
				$query->from($modelTable->getTableName())->where($this->getWhere($fieldNames,$fieldInformation).' '.$orderBy.'LIMIT '.($this->limit+1).' OFFSET '.($this->limit*($this->currentPage-1)));
				
				$results = $modelTable->doSelect($query);
				
				if(count($results)>$this->limit){
					$this->showNext = true;
					$this->nextPage = $this->currentPage + 1;
					unset($results[$this->limit]);
				}
				
			} catch (\Exception $e) {
				//just log
			}
			
			foreach($results as $result){
				$items = array();
				$ids = array();
				foreach($fieldNames as $fieldName){
					if($fieldInformation[$fieldName]['is_foreign_reference']){
						$items[$fieldName] = $result[$modelTable->getModelName()]->getValueFromKey($fieldInformation[$fieldName]['use_model']);
					} else {
						$items[$fieldName] = $result[$modelTable->getModelName()]->getValueFromKey($fieldName);
						//$items[$fieldName] = $result[$modelTable->getModelName()]->{$fieldInformation[$fieldName]['get_function']}();
					}
					if($fieldInformation[$fieldName]['mysql_key'] == 'PRI'){
						$ids[$fieldName] = $result[$modelTable->getModelName()]->getValueFromKey($fieldName);
					}
				}
				$items['___pks'] = rawurlencode(base64_encode(json_encode($ids)));
				$this->displayItems[] = $items;
			}
			
			$this->databaseName = $_GET['database'];
			$this->modelName = $_GET['model'];
			
			
		} else {
			$this->displayListErrors = i18n('admin.list.error.model-table-not-found','Model Table Not Found...');
		}
		
		
		return 'project/apps/back_end/widgets/display_list/views/display_list.twig';
	}
	
	/**
	 * Function - Get Where
	 * @param mixed $fieldNames Field Names
	 * @param mixed $fieldInformation Field Information
	 */
	protected function getWhere($fieldNames,$fieldInformation){
		$filterArray = isset($_POST['filter'])? $_POST['filter'] : array();
		$where = '';
		if(!empty($filterArray)){
			foreach($filterArray as $key => $value){
				if((string)$value!=''){
					if(in_array($key,$fieldNames)){
						if(preg_match('/^(text|varchar)/',$fieldInformation[$key]['mysql_type'])){
							$where .= '`'.$key.'` LIKE "%'.addslashes($value).'%" AND ';
						} else {
							$where .= '`'.$key.'` = "'.addslashes($value).'" AND ';
						}
					}
				}
			}
			$where = preg_replace('/\s{1}AND\s{1}$/','',$where);
		}
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