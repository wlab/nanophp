<?php
namespace project\lib\twig\filters;

class CoreFilters extends \Twig_Extension {
	
	public function getFilters() {
		return array(
			'i18n' => new \Twig_Filter_Function('i18n'),
			'rawurlencode' => new \Twig_Filter_Function('rawurlencode'),
			'strcap' => new \Twig_Filter_Function(__CLASS__.'::strcap'),
			'inc' => new \Twig_Filter_Function(__CLASS__.'::inc'),
			'htmlencode' => new \Twig_Filter_Function('htmlentities'),
			'htmldecode' => new \Twig_Filter_Function('html_entity_decode'),
			'striptags' => new \Twig_Filter_Function('strip_tags'),
			'dump' => new \Twig_Filter_Function('var_dump'),
			'inarray' => new \Twig_Filter_Function('in_array'),
		);
	}

	public function getName() {
	 	return 'project';
	}
	
	public static function strcap($str,$len) {
		return (strlen($str)>$len)? substr($str,0,$len).'...' : $str;
	}
	
	public static function inc($str) {
		switch($str){
			case 'log':
				if(\project\config\Config::get('logging_bar')==true){
					$loggingBarWidget = new \project\globals\widgets\loggingbar\LoggingBar();
					return $loggingBarWidget->getRenderedWidget();
				}
				break;
		}
		return '';
	}
}