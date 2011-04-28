<?php
namespace project\apps\back_end\widgets\add;

/**
 * Add class 
 * 
 * Add
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package widgets
 * @subpackage add
 */
class Add extends \project\apps\back_end\templates\WidgetTemplate {

	protected $errorStack = array();
	protected $model = null;

	/**
	 * Function - Post Model Save
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	protected function postModelSave(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		$routing->getResponse()->pageRedirect();
	}

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->isSaved = ($routing->getRequest()->getOnceOnlyCookie('add-form-saved')===null)? false : true;
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			$fieldNames = array_keys($fieldInformation);
			
			if(!empty($_POST)){
				$modelClassName = 'project\db\om\\'.$_GET['database'].'\\'.$_GET['model'];
				$this->model = new $modelClassName();
				//this is a post, so try and save
				foreach($_POST[$modelTable->getModelName()] as $key => $value){
					try{
						$this->model->$fieldInformation[$key]['set_function']($value);
					} catch (\Exception $e) {
						$this->errorStack[$key] = i18n('admin.add.error.could-not-set-value','Could not set value for \'{key}\' as it failed validation',array('{key}' => $key));
					}
				}
				if(empty($this->errorStack)){
					try{
						$this->model->save();
						$ids = array();
						foreach($fieldNames as $fieldName){
							if($fieldInformation[$fieldName]['mysql_key'] == 'PRI'){
								$ids[$fieldName] = $this->model->getValueFromKey($fieldName);
							}
						}
						$routing->getResponse()->setOnceOnlyCookie('add-form-saved','');
						if($_POST['submit-form']=='save-preview'){
							$routing->getResponse()->setOnceOnlyCookie('add-form-record-preview',rawurlencode(base64_encode(json_encode($ids))));
						}
						$this->postModelSave($routing,$pageInstance);
					} catch (\Exception $e) {
						//just log
						$this->errorStack[] = i18n('admin.add.error.unable-to-save','Unable to save...');
					}
				}
			}
	
			$this->addForm = array();
			
			foreach($fieldInformation as $key => $details){
				if($fieldInformation[$key]['mysql_extra']!='auto_increment'){
					$inputType = $this->getType($details);
					//check to see if the type can be added via manual input...
					if($inputType!==null){
						$inputValue = isset($_POST[$modelTable->getModelName()][$key])? $_POST[$modelTable->getModelName()][$key] : $fieldInformation[$key]['mysql_default'];
						$this->addForm[$key] = array(
							'label' => array(
								'value' => i18n('admin.label-for-field.'.$_GET['database'].'.'.$_GET['model'].'.'.$key,$this->getLabel($key,$details))
							),
							'input' => array(
								'inputType' => $inputType,
								'inputName' => $modelTable->getModelName().'['.$key.']',
								'inputValue' => $inputValue
							)
						);
					}
				}
			}
		
			$this->addButton = array(
				'buttonValue' => 'save',
				'buttonLabel' => i18n('admin.add.save-button-value','Save')
			);
			
			$this->addAndPreviewButton = array(
				'buttonValue' => 'save-preview',
				'buttonLabel' => i18n('admin.add.save-and-preview-button-value','Save & Preview')
			);
			
		} else {
			$this->errorStack[] = i18n('admin.add.error.unable-find-data-model','Unable to find data model...');
		}
		
		$this->addErrors = $this->errorStack;
		$this->databaseName = $_GET['database'];
		$this->modelName = $_GET['model'];
		
		return 'project/apps/back_end/widgets/add/views/add.twig';
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
					return 'textarea';
					break;
				case 'date':
					return 'text';
					break;
				case 'datetime':
					return 'text';
					break;
				case 'tinyint':
					return 'text';
					break;
				case 'smallint':
					return 'text';
					break;
			}
		}
		return null;
	}
	
}