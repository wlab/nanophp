<?php
namespace nano\core\cli;

//This is here because PHPUnit seems to get very confused!
require_once 'nano/core/cli/base/Base.class.php';

/**
 * Main class for the command-line interface utilities.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.cli
 */
class Cli extends \nano\core\cli\base\Base {
	
	/**
	 * Function - __construct
	 */
	public function __construct() {
		\nano\core\view\View::load('nano/core/cli/views/header.php');
		$this->execute();
		\nano\core\view\View::load('nano/core/cli/views/footer.php');
	}
	
	/**
	 * Function - Execute
	 */
	public function execute() {
		if(count($_SERVER['argv'])>1){
			if(preg_match('/([\w\d\_\-]+)\:([\w\d\_\-]+)/',$_SERVER['argv'][1],$matches)){
				$file = 'project/cli/'.$matches[1].'/'.$matches[2].'.class.php';
				if(file_exists($file)){
					$class = 'project\cli\\'.$matches[1].'\\'.$matches[2];
					new $class();
				}else{
					$this->termPrint('Failed to find \''.$file.'\'', self::FORMAT_RED, self::FORMAT_BOLD);
				}
			}else{
				$this->termPrint('Failed to match pattern... (e.g. cron:ExampleFile)', self::FORMAT_RED, self::FORMAT_BOLD);
			}
		}else{
			$filesAndFolders = \nano\core\helpers\FileSystem::getFilesAndFoldersInDirectory('project/cli');
			$folders = $filesAndFolders['folders'];
			foreach($folders as $folderPath => $folderName){
				$this->termPrintln($folderName.':');
				$nestedFilesAndFolders = \nano\core\helpers\FileSystem::getFilesAndFoldersInDirectory($folderPath);
				$nestedFiles = $nestedFilesAndFolders['files'];
				foreach($nestedFiles as $filePath => $fileName){
					$this->termPrintln("\t".preg_replace('/\..*?$/','',$fileName));
				}
				$this->termPrintln('');
			}
		}
	}
}