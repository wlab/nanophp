<?php
namespace nano\core\test;

/**
 * Base class for unit testing using PHPUnit.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.test
 */
class Unit extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Register NanoPHP autoloaders and set the environment.
	 */
	public function setUp() {
		\nano\core\autoloader\Autoloader::register();
		\nano\core\config\Config::setEnvironment($_SERVER['PROJECT_ENV']);
	}
	
}