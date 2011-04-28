<?php
namespace nano\core\api;

/**
 * Api class 
 * 
 * Api
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.api
 */
class Api {
	
	/**
	 * Function - __construct
	 */
	public function __construct() {
		$routing = \nano\core\routing\Routing::getInstance();
		switch($_SERVER['REQUEST_METHOD']){
			case 'GET':
				$this->executePreGet($routing);
				$this->executeGet($routing);
				$this->executePostGet($routing);
				break;
			case 'POST':
				$this->executePrePost($routing);
				$this->executePost($routing);
				$this->executePostPost($routing);
				break;
			case 'PUT':
				$this->executePrePut($routing);
				$this->executePut($routing);
				$this->executePostPut($routing);
				break;
			case 'DELETE':
				$this->executePreDelete($routing);
				$this->executeDelete($routing);
				$this->executePostDelete($routing);
				break;
			default:
				$this->executePreGet($routing);
				$this->executeGet($routing);
				$this->executePostGet($routing);
				break;
		}
	}
	
	/**
	 * Function - Execute Pre Get
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePreGet(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Get
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executeGet(\nano\core\routing\Routing $routing){
		echo 'Received GET Request';
	}
	
	/**
	 * Function - Execute Post Get
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePostGet(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Pre Post
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePrePost(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Post
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePost(\nano\core\routing\Routing $routing){
		echo 'Received POST Request';
	}
	
	/**
	 * Function - Execute Post Post
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePostPost(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Pre Put
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePrePut(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Put
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePut(\nano\core\routing\Routing $routing){
		echo 'Received PUT Request';
	}
	
	/**
	 * Function - Execute Post Put
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePostPut(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Pre Delete
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePreDelete(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Function - Execute Delete
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executeDelete(\nano\core\routing\Routing $routing){
		echo 'Received DELETE Request';
	}
	
	/**
	 * Function - Execute Post Delete
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function executePostDelete(\nano\core\routing\Routing $routing){
		
	}
	
}