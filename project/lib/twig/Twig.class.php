<?php
namespace project\lib\twig;

/**
 * Autoloader for Twig template language.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.lib.twig
 */
class Twig {
	
	protected static $twig = null;
	
	final private function __construct() {}
	final private function __clone() {}
	
	public static function configure() {
		$loader = new \Twig_Loader_Filesystem($_SERVER['SCRIPTS_LOAD_FROM']);
		$cachePath = (\nano\core\config\Config::get('twig_cache_path')!==null)? \nano\core\config\Config::get('twig_cache_path') : sys_get_temp_dir();
		self::$twig = new \Twig_Environment($loader, array(
			'debug' => \nano\core\config\Config::get('twig_debug'),
			'cache' => $cachePath,
			'auto_reload' => \nano\core\config\Config::get('twig_auto_reload'),
			'trim_blocks' => true,
			'autoescape' => false
		));
		self::$twig->addExtension(new \project\lib\twig\filters\CoreFilters());
	}
	
	public static function getInstance() {
		return self::$twig;
	}
}