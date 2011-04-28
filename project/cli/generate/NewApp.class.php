<?php
namespace project\cli\generate;

class NewApp extends \nano\core\cli\base\Base {
	/**
	 * Function: Construct - Execute Task
	 *
	 * @author Chris Beck
	 */
	
	protected function execute() {
		$projectName = $this->getProjectName();
		//------
		//create project folder
		//------
		mkdir('project/apps/'.$projectName);
		//------
		//generate new example routing file
		//------
		$newProjectRouting = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newProjectRouting.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		//------
		//generate new example config file
		//------
		file_put_contents('project/apps/'.$projectName.'/Routing.class.php',$newProjectRouting);
		$newProjectConfig = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newProjectConfig.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/Config.class.php',$newProjectConfig);
		//------
		//generate new example folder structure
		//------
		mkdir('project/apps/'.$projectName.'/pages/index/views',0777,true);
		mkdir('project/apps/'.$projectName.'/templates',0777,true);
		mkdir('project/apps/'.$projectName.'/layouts',0777,true);
		mkdir('project/apps/'.$projectName.'/widgets/header/views',0777,true);
		//------
		//generate new example page
		//------
		$newExampleProjectPage = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectIndexPage.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/pages/index/Index.class.php',$newExampleProjectPage);
		//------
		//generate new example page view
		//------
		$newExampleProjectPageView = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectIndexPageView.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/pages/index/views/index.twig',$newExampleProjectPageView);
		//------
		//generate new example template
		//------
		$newExampleProjectTemplateClass = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectTemplateClass.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/templates/PageTemplate.class.php',$newExampleProjectTemplateClass);
		//------
		//generate new example layout
		//------
		$newExampleProjectLayout = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectTwigLayout.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/layouts/layout.twig',$newExampleProjectLayout);
		//------
		//generate new example widget
		//------
		$newExampleProjectHeaderWidget = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectHeaderWidget.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/widgets/header/Header.class.php',$newExampleProjectHeaderWidget);
		//------
		//generate new example widget view
		//------
		$newExampleProjectHeaderWidgetView = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newExampleProjectHeaderWidgetView.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/widgets/header/views/header.php',$newExampleProjectHeaderWidgetView);
		//------
		//generate new example unit tests
		//------
		mkdir('project/apps/'.$projectName.'/tests/unit/conf',0777,true);
		mkdir('project/apps/'.$projectName.'/tests/unit/lib',0777,true);		
		$newUnitBootStrap = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newUnitBootStrap.php',
			array(
				
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/tests/unit/conf/bootstrap.php',$newUnitBootStrap);
		$newUnitConf = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newUnitConf.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/tests/unit/conf/phpunit.xml',$newUnitConf);
		$newUnitTestExample = \nano\core\view\Twiglet::load(
			'project/cli/generate/views/newUnitTestExample.php',
			array(
				'projectName' => $projectName
			),
			true
		);
		file_put_contents('project/apps/'.$projectName.'/tests/unit/lib/ExampleTest.php',$newUnitTestExample);
		
		$this->termPrintln('You new project "'.$projectName.'" has been created in project/apps', self::FORMAT_GREEN, self::FORMAT_BOLD);
	}
	
	private function getProjectName() {
		try{
			$projectName = $this->readLine('Please enter a name for the new project','/^[a-z]{1}[a-z\_]*$/',$retries=2,'Must start with a letter (a-z) which is lowercase and only contain characters a-z and \'_\'');
		} catch (\Exception $e) {
			echo 'Failed to get an acceptable name for your new project';
			exit;
		}
		if(file_exists('project/apps/'.$projectName)){
			$this->termPrintln('It looks like the project name "'.$projectName.'" already exists...', self::FORMAT_RED);
			return $this->getProjectName();
		} else {
			return $projectName;
		}
	}
}