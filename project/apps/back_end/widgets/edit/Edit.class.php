<?php
namespace project\apps\back_end\widgets\edit;

/**
 * Edit class 
 * 
 * Edit
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package widgets
 * @subpackage edit
 */
class Edit extends \project\apps\back_end\templates\WidgetTemplate {

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
		
		$this->isSaved = ($routing->getRequest()->getOnceOnlyCookie('edit-form-saved')===null)? false : true;
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			$fieldNames = array_keys($fieldInformation);
			$jsonObj = null;
			if(isset($_GET['ids'])){
				$jsonObj = json_decode(base64_decode(rawurldecode($_GET['ids'])));
			}
			if(is_object($jsonObj)){
				$result = null;			
				try{
					$query = new \nano\core\db\core\SelectQuery();
					$query->from($modelTable->getTableName())->where($this->getWhere($jsonObj,$fieldNames).' LIMIT 1');
					$results = $modelTable->doSelect($query);
					$result = isset($results[0])? $results[0] : null;
				} catch (\Exception $e) {
					//just log
				}
				if($result==null){
					$this->errorStack[] = i18n('admin.edit.error.unable-to-find-record','Unable to to find record...');
				} else {
					if(!empty($_POST)){
						$this->model = $result[$modelTable->getModelName()];
						//this is a post, so check and see if record should be deleted first...
						if(empty($this->errorStack)){
							if($_POST['submit-form']=='delete'){
								try{
									$this->model->delete();
								} catch (\Exception $e) {
									//just log
									$this->errorStack[] = i18n('admin.edit.error.unable-to-delete','Unable to delete...');
								}
								if(empty($this->errorStack)){
									$routing->getResponse()->setOnceOnlyCookie('edit-form-record-deleted','');
									$routing->getResponse()->pageRedirect('/'.$_GET['database'].'/'.$_GET['model'].'/list');
								}
							} else {
								//this is a post, so try and save
								foreach($_POST[$modelTable->getModelName()] as $key => $value){
									try{
										$this->model->$fieldInformation[$key]['set_function']($value);
									} catch (\Exception $e) {
										$this->errorStack[$key] = i18n('admin.edit.error.could-not-set-value','Could not set value for \'{key}\' as it failed validation',array('{key}' => $key));
									}
								}
								if(empty($this->errorStack)){
									try{
										$this->model->save();
										$routing->getResponse()->setOnceOnlyCookie('edit-form-saved','');
										if($_POST['submit-form']=='save-preview'){
											$routing->getResponse()->setOnceOnlyCookie('edit-form-record-preview','');
										}
										$this->postModelSave($routing,$pageInstance);
									} catch (\Exception $e) {
										//just log
										$this->errorStack[] = i18n('admin.edit.error.unable-to-save','Unable to save...');
									}
								}
							}
						}
					}
			
					$this->editForm = array();
					foreach($fieldInformation as $key => $details){
						$inputType = $this->getType($details);
						//check to see if the type can be edited...
						if($inputType!==null){
							
							if(isset($_POST[$modelTable->getModelName()][$key])){
								$inputValue = $_POST[$modelTable->getModelName()][$key];
							} else {
								if($fieldInformation[$key]['is_foreign_reference']){
									$inputValue = $result[$modelTable->getModelName()]->getValueFromKey($fieldInformation[$key]['use_model']);
								} else {
									$inputValue = $result[$modelTable->getModelName()]->{$fieldInformation[$key]['get_function']}();
								}
							}
							
							$this->editForm[$key] = array(
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
				
					$this->editButton = array(
						'buttonValue' => 'save',
						'buttonLabel' => i18n('admin.edit.save-button-value','Save')
					);
					
					$this->editAndPreviewButton = array(
						'buttonValue' => 'save-preview',
						'buttonLabel' => i18n('admin.edit.save-and-preview-button-value','Save & Preview')
					);

					$delete = new \project\apps\back_end\widgets\delete\Delete();
					$this->delete = $delete->getRenderedWidget();
				}
			} else {
				$this->errorStack[] = i18n('admin.edit.error.unable-to-get-keys-from-pk','Unable to to find record...');
			}
		} else {
			$this->errorStack[] = i18n('admin.edit.error.unable-find-data-model','Unable to find data model...');
		}
		
		$this->editErrors = $this->errorStack;
		$this->databaseName = $_GET['database'];
		$this->modelName = $_GET['model'];
		
		return 'project/apps/back_end/widgets/edit/views/edit.twig';
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