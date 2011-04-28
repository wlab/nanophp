<?php
namespace project\cli\generate;

class NewTask extends \nano\core\cli\base\Base {
	/**
	 * Function: Construct - Create a New Task
	 *
	 * @author Chris Beck
	 */
	public function __construct() {
		$var = isset($_SERVER['argv'][2])? $_SERVER['argv'][2] : '';
		if(preg_match('/^\-\-name=([A-Z]{1}[a-zA-Z0-9]+)$/',trim($var),$matches)){
			$filePath = 'project/cli/task/'.$matches[1].'.class.php';
			if(!file_exists($filePath)){
				$fileBody = \nano\core\view\Twiglet::load(
					'project/cli/generate/views/newTask.php',
					array(
						'className' => $matches[1]
					),
					true
				);
				file_put_contents($filePath,$fileBody);
				echo 'The file ('.$filePath.') has been generated!'."\n";
			}else{
				echo 'Opps, it looks like that file ('.$filePath.') already exists!'."\n";
			}
		}else{
			echo 'Please enter the name of the task file in the form \'--name=Example\''."\n";
		}
	}
}