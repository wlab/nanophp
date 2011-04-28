<?php
namespace project\cli\generate;

class DatabaseModels extends \nano\core\cli\base\Base {
	/**
	 * Function: Construct - Execute Task
	 *
	 * @author Chris Beck
	 */
	
	protected function execute() {
		$environment = null;
		$connection = null;
		$database = null;
		$table = null;
		foreach($_SERVER['argv'] as $arg) {
			if(preg_match('/\-\-(.+)=(.+)/',trim($arg),$matches)){
				${$matches[1]} = $matches[2];
			}
		}
		if($environment==null){
			$this->termPrintln('Please set the correct environment to use with --environment=Dev (for example)...', self::FORMAT_BLUE, self::FORMAT_BG_WHITE, self::FORMAT_BOLD);
			$this->termPrintln('--connection=[default=\'default\']');
			$this->termPrintln('--database=[default=\'all\']');
			$this->termPrintln('--table=[default=\'all\']');
		} else {
			if(file_exists('project/config/environments/'.$environment.'.class.php')){
				\nano\core\config\Config::setEnvironment($environment);
				$databases = \nano\core\config\Config::get('databases');
				if($connection==null) {
					if(isset($databases['default'])){
						echo 'Using "default" connection'."\n";
						$this->fetchDatabases('default',$database,$table);
					} else {
						echo 'FAILED: no "default" connection found under databases, please specify a database using --database=dbname (for example)...'."\n";
					}
				} else {
					if(isset($databases[$connection])){
						echo 'Using "'.$connection.'" connection'."\n";
						$this->fetchDatabases($connection,$database,$table);
					} else {
						echo 'FAILED: no "'.$connection.'" connection found under databases, please specify a database using --database=dbname (for example)...'."\n";
					}
				}
			} else {
				echo 'FAILED: environment "'.$environment.'" not found. Please check in project/config/environments...'."\n";
			}
		}
	}
	
	private function fetchDatabases($connection,$database,$table) {
		$db = \nano\core\db\core\Database::open($connection);
		if($db->isOpen()){
			$results = $db->select('SHOW DATABASES');
			$databases = array();
			foreach($results as $result) {
				$databases[] = $result['Database'];
			}
			if($database!=null){
				if(!in_array($database,$databases)){
					echo 'FAILED: the following database does not appear to exist "'.$database.'"...'."\n";
				} else {
					$this->fetchTables($db,$connection,$database,$table);
				}
			} else {
				if($table==null){
					foreach($databases as $database){
						$this->fetchTables($db,$connection,$database);
					}
				} else {
					echo 'FAILED: please specify a database in which the table is present...'."\n";
				}
			}
		}
	}
	
	private function fetchTables($db,$connection,$database,$table=null) {
		$databaseInformation = array();
		if($db->isOpen()){
			$results = $db->select('SHOW TABLES IN '.$database);
			$map = array();
			foreach($results as $result) {
				$map[$result['Tables_in_'.$database]] = $this->getModelNameFromTableName($result['Tables_in_'.$database]);
				if($table==null) {
					$databaseInformation[] = $this->generateModel($db,$connection,$database,$result['Tables_in_'.$database]);
				} else if($table==$result['Tables_in_'.$database]) {
					$databaseInformation[] = $this->generateModel($db,$connection,$database,$result['Tables_in_'.$database]);
				}
			}
			if($table==null){
				$this->generateMap($map,$database,$databaseInformation);
			} else {
				echo 'WARNING: When updating a single table, the map file is NOT updated. It is recommended to generate from --database=[database] where possible...';
			}
		}
	}
	
	private function getModelNameFromTableName($tableName){
		$partModelNames = explode('_',$tableName);
		$modelName = '';
		if(count($partModelNames)>1){
			foreach($partModelNames as $partModelName){
				$modelName .= ucfirst($partModelName);
			}
		} else {
			$modelName = ucfirst($tableName);
		}
		return preg_replace('/s$/','',$modelName);
	}
	
	private function generateMap($map,$database,$databaseInformation){
		$folderPath = 'project/db/om/'.$database.'/map';
		if(!file_exists($folderPath)){
			mkdir($folderPath);
		}
		$mapPath = $folderPath.'/Map.class.php';
		
		$classBody = "\t".'public static $tableNameToModelName = array('."\n";
		if(count($map)>0){
			foreach($map as $tableName => $modelName){
				$classBody .= "\t\t".'\''.$tableName.'\' => \''.$modelName.'\','."\n";
			}
			$classBody = substr($classBody,0,-2)."\n";
		}
		$classBody .= "\t".');'."\n";
		
		$classBody .= "\t".'public static $modelNameToTableName = array('."\n";
		if(count($map)>0){
			foreach($map as $tableName => $modelName){
				$classBody .= "\t\t".'\''.$modelName.'\' => \''.$tableName.'\','."\n";
			}
			$classBody = substr($classBody,0,-2)."\n";
		}
		$classBody .= "\t".');'."\n";
		
		$classBody .= "\t".'public static $mapTablesToColumns = array('."\n";
		if(count($map)>0){
			foreach($databaseInformation as $tables){
				$tableName = key($tables);
				$classBody .= "\t\t".'\''.$tableName.'\' => array(';
				if(count($tables)>0){
					foreach($tables[$tableName] as $column){
						$columnKey = $column;
						if(preg_match('/^((?<database>.*?)\.)?(?<model>[A-Z]{1}.+?)$/',$column,$matches)){
							$columnKey = $matches['model'];
						}
						$classBody .= '\''.$columnKey.'\' => \''.$column.'\',';
					}
					$classBody = substr($classBody,0,-1);
				}
				$classBody .= '),'."\n";
			}
			$classBody = substr($classBody,0,-2)."\n";
		}
		$classBody .= "\t".');'."\n";
		
		$fileBody = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newMap.php',
			array(
				'database' => $database,
				'classBody' => $classBody
			),
			true
		);
		file_put_contents($mapPath,$fileBody);
	}
	
	private function generateModel($db,$connection,$database,$table){
		$tableInformation = array();
		echo 'Generating new model, base model, model table and base model table for database:"'.$database.'" and table:"'.$table.'"'."\n";
		if($db->isOpen()){
			$columns = $db->select('SHOW COLUMNS IN '.$table.' IN '.$database);
			$modelName = $this->getModelNameFromTableName($table);	
			//check folders exist
			$folderPath = 'project/db/om/'.$database;
			if(!file_exists($folderPath)){
				mkdir($folderPath);
				mkdir($folderPath.'/base');
			}
			
			$modelPath = $folderPath.'/'.$modelName.'.class.php';
			$modelTablePath = $folderPath.'/'.$modelName.'Table.class.php';
			$baseModelPath = $folderPath.'/base/Base'.$modelName.'.class.php';
			$baseModelTablePath = $folderPath.'/base/Base'.$modelName.'Table.class.php';
			if(!file_exists($modelPath)){
				$fileBody = \nano\core\view\Twiglet::load(
					'project/cli/generate/views/newModel.php',
					array(
						'database' => $database,
						'modelName' => $modelName,
						'classBody' => ''
					),
					true
				);
				file_put_contents($modelPath,$fileBody);
			}
			if(!file_exists($modelTablePath)){
				$fileBody = \nano\core\view\Twiglet::load(
					'project/cli/generate/views/newModelTable.php',
					array(
						'database' => $database,
						'modelName' => $modelName,
						'classBody' => ''
					),
					true
				);
				file_put_contents($modelTablePath,$fileBody);
			}
			
			$primaryKeys = array();
			$fieldNameMapStr = "\t".'protected $newFieldNameMap = array('."\n";
			$fieldsStr = "\t".'protected $fields = array('."\n";
			$validationFunctionStrs = array();
			$setFunctionStrs = array();
			$getFunctionStrs = array();
			if(count($columns)>0){
				$columnInformation = array();
				foreach($columns as $column){
					
					$useModel = $modelName;
					$useDatabase = $database;
					$isForeignReference = 'false';
					$newFieldName = $column['Field'];
					
					if(preg_match('/^((?<database>.+?)\.)?(?<model>[A-Z]{1}.+?)$/',$column['Field'],$matches)){
						$useModel = $matches['model'];
						$newFieldName = $useModel;
						if(isset($matches['database'])){
							if($matches['database']!=''){
								$useDatabase = $matches['database'];
							}
						}
						$isForeignReference = 'true';
					}
					
					$columnInformation[] = $column['Field'];
					
					if($column['Key']=='PRI'){
						$primaryKeys[] = $newFieldName;
					}
					
					$partColumnNames = explode('_',$newFieldName);
					
					$setFunctionName = 'set';
					$getFunctionName = 'get';
					$validationFunctionName = 'validate';
					if(count($partColumnNames)>1){
						foreach($partColumnNames as $partColumnName){
							$setFunctionName .= ucfirst($partColumnName);
							$getFunctionName .= ucfirst($partColumnName);
							$validationFunctionName .= ucfirst($partColumnName);
						}
					} else {
						$getFunctionName .= ucfirst($newFieldName);
						$setFunctionName .= ucfirst($newFieldName);
						$validationFunctionName .= ucfirst($newFieldName);
					}
					
					$setFunctionStr = "\t".'public function '.$setFunctionName.'($value){'."\n";
					$setFunctionStr .= "\t\t".'if(\project\db\om\\'.$database.'\\'.$modelName.'::'.$validationFunctionName.'($value)){'."\n";
					$setFunctionStr .= "\t\t\t".'$this->'.$newFieldName.' = $value;'."\n";
					$setFunctionStr .= "\t\t".'} else {'."\n";
					$setFunctionStr .= "\t\t\t".'throw new \nano\core\exception\ValidationException(\'Validation of column `'.$column['Field'].'` failed\');'."\n";
					$setFunctionStr .= "\t\t".'}'."\n";
					$setFunctionStr .= "\t".'}'."\n";
					$setFunctionStrs[] = $setFunctionStr;
					
					$getFunctionStrs[] = "\t".'public function '.$getFunctionName.'(){'."\n\t\t".'return $this->'.$newFieldName.';'."\n\t".'}'."\n";
					$validationFunctionStrs[] = "\t".'public static function '.$validationFunctionName.'($value){'."\n\t\t".'return true;'."\n\t".'}'."\n";
					
					$fieldNameMapStr .= "\t\t".'\''.$newFieldName.'\' => \''.$column['Field'].'\','."\n";
					
					$fieldsStr .= "\t\t".'\''.$column['Field'].'\' => array('."\n";
					
					$fieldsStr .= "\t\t\t".'\'mysql_type\' => \''.$column['Type'].'\','."\n";
					$fieldsStr .= "\t\t\t".'\'mysql_is_null\' => \''.$column['Null'].'\','."\n";
					$fieldsStr .= "\t\t\t".'\'mysql_key\' => \''.$column['Key'].'\','."\n";
					$fieldsStr .= "\t\t\t".'\'mysql_default\' => \''.$column['Default'].'\','."\n";
					$fieldsStr .= "\t\t\t".'\'mysql_extra\' => \''.$column['Extra'].'\','."\n";
					$fieldsStr .= "\t\t\t".'\'is_foreign_reference\' => '.$isForeignReference.','."\n";
					$fieldsStr .= "\t\t\t".'\'use_model\' => \''.$useModel.'\','."\n";
					$fieldsStr .= "\t\t\t".'\'use_database\' => \''.$useDatabase.'\','."\n";
					$fieldsStr .= "\t\t\t".'\'set_function\' => \''.$setFunctionName.'\','."\n";
					$fieldsStr .= "\t\t\t".'\'validation_function\' => \''.$validationFunctionName.'\','."\n";
					$fieldsStr .= "\t\t\t".'\'get_function\' => \''.$getFunctionName.'\','."\n";
					
					$fieldsStr .= "\t\t".'),'."\n";
				}
				$tableInformation[$table] = $columnInformation;
				$fieldNameMapStr = substr($fieldNameMapStr,0,-2)."\n";
				$fieldsStr = substr($fieldsStr,0,-2)."\n";
			}
			$fieldsStr .= "\t".');'."\n";
			$fieldNameMapStr .= "\t".');'."\n";
			
			$retrieveByPkStr = '';
					
			$primaryKeyStr = '';
			if(count($primaryKeys)>0){
				$retrieveByPkStr = "\t".'public function retrieveByPk(';
				$primaryKeyStr = "\t".'protected $primaryKey = array(';
				foreach($primaryKeys as $primaryKey){
					$primaryKeyStr .= '\''.$primaryKey.'\',';
					$retrieveByPkStr .= '$'.$primaryKey.',';
				}
				$primaryKeyStr = substr($primaryKeyStr,0,-1).');'."\n";
				$retrieveByPkStr = substr($retrieveByPkStr,0,-1).') {'."\n";
				$retrieveByPkStr .= "\t\t".'$query = new \nano\core\db\core\SelectQuery();'."\n";
				$retrieveByPkStr .= "\t\t".'$query->from(\''.$table.'\')->where(\'';
				foreach($primaryKeys as $primaryKey){
					$retrieveByPkStr .= '`'.$table.'`.`'.$primaryKey.'` = "\'.addslashes($'.$primaryKey.').\'" AND ';
				}
				$retrieveByPkStr = substr($retrieveByPkStr,0,-4).'LIMIT 1\');'."\n";
				$retrieveByPkStr .= "\t\t".'$results = $this->doSelect($query);'."\n";
				$retrieveByPkStr .= "\t\t".'return isset($results[0][\''.$modelName.'\'])? $results[0][\''.$modelName.'\'] : null;'."\n";
				$retrieveByPkStr .= "\t".'}'."\n";
			} else if(count($primaryKeys)==1) {
				$primaryKeyStr = "\t".'protected $primaryKey = \'array()\';'."\n";
			}			

			$classBody = "\n";
			$classBody .= "\t".'protected $modelName = \''.$modelName.'\';'."\n";
			$classBody .= $primaryKeyStr;
			$classBody .= "\t".'protected $dbConfig = \''.$connection.'\';'."\n";
			$classBody .= "\t".'protected $dbName = \''.$database.'\';'."\n";
			$classBody .= "\t".'protected $tableName = \''.$table.'\';'."\n";
			$classBody .= $fieldsStr;
			$classBody .= $fieldNameMapStr;
			
			$fileBody = \nano\core\view\Twiglet::load(
				'project/cli/generate/views/newBaseModelTable.php',
				array(
					'database' => $database,
					'modelName' => $modelName,
					'classBody' => $classBody,
					'retrieveByPk' => $retrieveByPkStr
				),
				true
			);
			file_put_contents($baseModelTablePath,$fileBody);
			
			foreach($setFunctionStrs as $setFunctionStr){
				$classBody .= $setFunctionStr;
			}
			
			foreach($getFunctionStrs as $getFunctionStr){
				$classBody .= $getFunctionStr;
			}
			
			foreach($validationFunctionStrs as $validationFunctionStr){
				$classBody .= $validationFunctionStr;
			}
			
			$fileBody = \nano\core\view\Twiglet::load(
				'project/cli/generate/views/newBaseModel.php',
				array(
					'database' => $database,
					'modelName' => $modelName,
					'classBody' => $classBody
				),
				true
			);
			file_put_contents($baseModelPath,$fileBody);
		}
		return $tableInformation;
	}
}