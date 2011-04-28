<?php
$_SERVER['SCRIPTS_LOAD_FROM'] = $_SERVER['DOCUMENT_ROOT'] . '/../';
require_once($_SERVER['SCRIPTS_LOAD_FROM'].'nano/core/autoloader/Autoloader.class.php');
\nano\core\autoloader\Autoloader::register();
\nano\core\config\Config::setEnvironment();
try{
	\nano\core\config\Config::setErrorReporting();
	\nano\core\config\Config::setDefaultExceptionHandler();
} catch (\Exception $e) { }
function i18n($key,$defaultValue='',$replacements=array()) {
	return \nano\core\i18n\I18n::getInstance()->get($key, $defaultValue, $replacements);
}
function view($path,$objs=array()) {
	\nano\core\view\View::load($path, $objs);
}
$log = \nano\core\log\Log::getInstance();
$routing = \nano\core\routing\Routing::getInstance();