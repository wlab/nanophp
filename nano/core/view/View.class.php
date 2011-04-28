<?php
namespace nano\core\view;

/**
 * View.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.view
 */
class View {
	/**
	 * Function - __construct
	 */
	final private function __construct() { }
	
	/**
	 * Function - __clone
	 */
	final private function __clone() { }
	
	/**
	 * Load template. Called by Page->render().
	 * @param string $path Path to template. If 'twig' is found in the path, Twig will be used for rendering.
	 * @param array $objs Array of variables to be passed to the rendering engine.
	 * @param $return If TRUE, the rendered template will be returned; otherwise, it will be output directly.
	 * @return mixed Template or void. See $return.
	 */
	public static function load($path, array $objs=array(), $return=false) {
		if($path!=null&&$path!=false){
			///TODO:APC: Any reason not to use the much faster stripos()?
			if(preg_match('/\.twig$/',$path)){
				//render using twig...
				return self::twigRender($path, $objs, (bool)$return);
			} else {
				//render using default nano renderer
				return self::nanoRender($path, $objs, (bool)$return);
			}
		}
	}
	
	/**
	 * Function - Twig Render
	 * @param mixed $path Path
	 * @param  $objs Objs
	 * @param  $return Return
	 * @return mixed
	 */
	private static function twigRender($path, $objs, $return){
		$twig = \project\lib\twig\Twig::getInstance();
		$twigTemplate = $twig->loadTemplate($path);
		$contents = $twigTemplate->render($objs);
		unset($objs);
		if($return){
			return $contents;
		}
		echo $contents;
		return true;
	}
	
	/**
	 * Function - Nano Render
	 * @param mixed $path Path
	 * @param  $objs Objs
	 * @param  $return Return
	 */
	private static function nanoRender($path, $objs, $return){
		foreach($objs as $key => $obj){
			${$key} = $obj;
		}
		unset($objs);
		if($return){
			ob_start();
			include $_SERVER['SCRIPTS_LOAD_FROM'].$path;
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		include $_SERVER['SCRIPTS_LOAD_FROM'].$path;
		return true;
	}
}