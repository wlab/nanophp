<?php
namespace project\apps\back_end\widgets\filter;

/**
 * Filter class 
 * 
 * Filter
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package widgets
 * @subpackage filter
 */
class Filter extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->filterErrors = array();
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			
			$this->filterForm = array();
			foreach($fieldInformation as $key => $details){
				$inputValue = isset($_POST['filter'][$key])? $_POST['filter'][$key] : '';
				$this->filterForm[$key] = array(
					'label' => array(
						'value' => i18n('admin.label-for-field.'.$_GET['database'].'.'.$_GET['model'].'.'.$key,$this->getLabel($key,$details))
					),
					'input' => array(
						'inputType' => $this->getType($details),
						'inputName' => 'filter['.$key.']',
						'inputValue' => $inputValue
					)
				);
			}
			
			$this->filterButton = array(
				'buttonValue' => 'Apply Filter',
				'buttonLabel' => i18n('admin.filter.apply-filter-button-value','Apply Filter')
			);
			
		} else {
			$this->filterErrors = i18n('admin.filter.error.model-not-found','Unable to find data model...');
		}
		return 'project/apps/back_end/widgets/filter/views/filter.twig';
	}
	
	/**
	 * Function - Get Label
	 * @param mixed $key Key
	 * @param mixed $details Details
	 */
	protected function getLabel($key,$details){
		$key = preg_replace('/(.*?)\.(.+)$/','$2',$key);
		$key = preg_replace('/([a-z]{1})([A-Z]{1})/','$1 $2',$key);
		$key = preg_replace('/\_([a-z]{1})/ie',"' '.strtoupper('\\1')",$key);
		return ucfirst($key);
	}
	
	/**
	 * Function - Get Type
	 * @param mixed $details Details
	 * @return mixed
	 */
	protected function getType($details){
		if(preg_match('/^([a-z]+)/',$details['mysql_type'],$matches)){
			switch($matches[1]){
				case 'int':
					return 'text';
					break;
				case 'varchar':
					return 'text';
					break;
				case 'char':
					return 'text';
					break;
				case 'text':
					return 'text';
					break;
				case 'date':
					return 'text';
					break;
				case 'datetime':
					return 'text';
					break;
			}
		}
		return 'text'; //default base case
	}
	
	
}