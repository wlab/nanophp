<?php
namespace project\globals\widgets\exception;

class Exception extends \nano\core\widget\Widget {
	
	private $exception = null;
	
	public function setParentException(\Exception $exception){
		$this->exception = $exception;
	}
	
	private function getExceptionData($exception){
		if($exception!==null){
			$data = array(
				'type' => get_class($exception),
				'message' => $exception->getMessage(),
				'trace_as_string' => $exception->getTraceAsString(),
				'file' => $exception->getFile(),
				'line' => $exception->getLine()
			);
			$this->exceptionData[] = $data;
			$this->getExceptionData($exception->getPrevious());
		}
	}
	
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->exceptionData = array();
		$this->getExceptionData($this->exception);
		
		return 'project/globals/widgets/exception/views/exception.twig';
	}
		
}