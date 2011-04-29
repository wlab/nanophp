<?php
namespace nano\core\exception;

/**
 * ValidationException
 * 
 * Thrown on the failure of data validation.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.exception
 */
class ValidationException extends \Exception {
	/**
	 * @param string $message Human-readable error message.
	 * @param int $code Numeric error code. Default 0.
	 * @param \Exception $previous Previous exception in chain.
	 */
	public function __construct($message,$code=0,\Exception $previous=null) {
		parent::__construct($message,$code,$previous);
		$log = \nano\core\log\Log::getInstance(); 
		$log->addError('ValidationException',$this,\nano\core\log\Log::PRIORITY_LEVEL_DEVLOG);
	}	
}