<?php
namespace project\apps\back_end\widgets\import;

/**
 * ModelImport class 
 * 
 * Model Import
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package widgets
 * @subpackage import
 */
class ModelImport extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->importErrors = array();
		$this->isSaved = false;
		
		if(file_exists($_SERVER['SCRIPTS_LOAD_FROM'].'project/db/om/'.$_GET['database'].'/'.$_GET['model'].'Table.class.php')){
			$modelTable = \nano\core\db\ORM::getInstance()->getTable($_GET['model'],$_GET['database']);
			$fieldInformation = $modelTable->getFieldInformation();
			$fieldNames = array_keys($fieldInformation);
			if(!empty($_FILES)){
				//file has been uploaded, so process
				switch($_FILES['import-file']['type']){
					case 'text/csv':
						$modelClassName = 'project\db\om\\'.$_GET['database'].'\\'.$_GET['model'];
						$mapClassName = 'project\db\om\\'.$_GET['database'].'\map\Map';
						if(($handle = fopen($_FILES['import-file']['tmp_name'],'r')) !== false){
							$cols = fgetcsv($handle,0,',','"','"');
							if($cols !== false){
								while(($data = fgetcsv($handle,0,',','"','"')) !== false){
									if(count($data)===count($cols)){
										$model = new $modelClassName();
										foreach($cols as $key => $col){
											if(isset($mapClassName::$mapTablesToColumns[$modelTable->getTableName()][$col])){
												try{
													$model->{$fieldInformation[$mapClassName::$mapTablesToColumns[$modelTable->getTableName()][$col]]['set_function']}($data[$key]);
												} catch (\Exception $e) {
													$this->importErrors[] = $e->getMessage();
												}
											} else {
												$this->importErrors[] = 'Database column `'.$col.'` not found in model map';
											}
										}
										try{
											$model->save();
											$this->isSaved = true;
										} catch (\Exception $e) {
											$this->importErrors[] = $e->getMessage();
										}
									}
								}
							}
						} else {
							$this->importErrors[] = 'Can\'t open file on the server';
						}
						fclose($handle);
						break;
					default:
						$this->importErrors[] = 'File type is not supported';
						break;
				}
				
			}
			$this->importForm[] = array(
				'label' => array(
					'value' => 'Please choose a file to upload'
				),
				'input' => array(
					'inputType' => 'file',
					'inputName' => 'import-file',
					'inputValue' => ''
				)
			);
			
			$this->importButton = array(
				'buttonValue' => 'upload-file',
				'buttonLabel' => 'Upload File'
			);
		}
		return 'project/apps/back_end/widgets/import/views/model_import.twig';
	}
}