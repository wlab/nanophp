<?php
namespace project\cli\task;

require_once $_SERVER['SCRIPTS_LOAD_FROM'].'lib/PHPUnit-3.5.14/PHPUnit/Autoload.php';

class RunUnitTests {
	public function __construct() {
		
		$environment = null;
		$project = null;

		foreach($_SERVER['argv'] as $arg) {
			if(preg_match('/\-\-(.+)=(.+)/',trim($arg),$matches)){
				${$matches[1]} = $matches[2];
			}
		}
		if($environment==null){
			echo 'Please set the correct environment to use with --environment=Dev (for example)...';
			exit;
		}
		if($project==null){
			echo 'Please set the correct project to use with --project=test (for example)...';
			exit;
		}

		$_SERVER['argv'] = array('phpunit','--configuration=project/apps/'.$project.'/tests/unit/conf/phpunit.xml');
		$_SERVER['argc'] = count($_SERVER['argv']);
		
		if(file_exists('project/config/environments/'.$environment.'.class.php')){
			
			$_SERVER['PROJECT_APP'] = $project;
			$_SERVER['PROJECT_ENV'] = $environment;

			\PHPUnit_TextUI_Command::main();
			
		} else {
			echo 'FAILED: environment "'.$environment.'" not found. Please check in project/config/environments...'."\n";
		}
		
	}
}