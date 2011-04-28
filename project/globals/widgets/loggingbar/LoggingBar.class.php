<?php
namespace project\globals\widgets\loggingbar;

class LoggingBar extends \nano\core\widget\Widget {

	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$log = \nano\core\log\Log::getInstance();
		
		$this->isAjaxRequest = $routing->isAjax();
		$this->memory = number_format((memory_get_peak_usage() / 1048576),2);
		$this->time = number_format($log->getExecuteTime(),2);
		$this->errors = $log->getErrors();
		$this->queries = $log->getQueries();
		$this->cachedInfos = $log->getCachedInfos();
		$this->environment = \nano\core\config\Config::getEnvironment();
		$this->config = \nano\core\config\Config::getConfig();

		return 'project/globals/widgets/loggingbar/views/log.php';
	}
	
}