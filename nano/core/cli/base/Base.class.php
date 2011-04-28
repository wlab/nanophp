<?php
namespace nano\core\cli\base;

/**
 * Base class 
 * 
 * Base
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.cli.base
 */
class Base {
	
	const FORMAT_NORMAL = '0;';
	const FORMAT_BOLD = '1;';
	const FORMAT_UNDERSCORE = '4;';
	const FORMAT_REVERSE = '7;';
	const FORMAT_BLACK = '30;';
	const FORMAT_RED = '31;';
	const FORMAT_GREEN = '32;';
	const FORMAT_YELLOW = '33;';
	const FORMAT_BLUE = '34;';
	const FORMAT_MAGENTA = '35;';
	const FORMAT_CYAN = '36;';
	const FORMAT_WHITE = '37;';
	const FORMAT_BG_BLACK = '40;';
	const FORMAT_BG_RED = '41;';
	const FORMAT_BG_GREEN = '42;';
	const FORMAT_BG_YELLOW = '43;';
	const FORMAT_BG_BLUE = '44;';
	const FORMAT_BG_MAGENTA = '45;';
	const FORMAT_BG_CYAN = '46;';
	const FORMAT_BG_WHITE = '47;';
	
	/**
	 * Function - __construct
	 */
	public function __construct() {
		$this->execute();
	}
	
	private final function output($argArray) {
		$str = '';
		$escapeSeq = '';
		foreach($argArray as $num => $arg){
			if($num==0){
				$str = $arg;
			}else{
				$escapeSeq .= $arg;
			}
		}
		if($escapeSeq=='') $escapeSeq = self::FORMAT_NORMAL;
		$escapeSeq = substr($escapeSeq, 0, -1).'m';
		///TODO:APC: Regex anything bad out from the $escapeSeq at this point.
		echo(chr(27)."[{$escapeSeq}{$str}".chr(27)."[0m");
	}
	
	protected final function termPrint() {
		$this->output(func_get_args());
	}
	
	protected final function termPrintln() {
		$args = func_get_args();
		if(!isset($args[0])) $args[0] = '';
		$args[0] .= "\n";
		$this->output($args);
	}
	
	protected final function readLine($prompt=null,$matches=null,$retries=2,$helpText=null){
		if($retries<0){
			throw new \Exception('Number of retries exhausted for \''.$prompt.'\'');
			return null;
		}
		if($prompt!=null){
			echo $prompt.' : ';
		}
		$line = trim(fgets(STDIN));
		if(is_array($matches)){
			if(in_array($line,$matches)){
				return $line;
			} else {
				if($helpText!=null){
					echo $helpText."\n";
				}
				$retries --;
				return $this->readLine($prompt,$matches,$retries,$helpText);
			}
		} else if($matches!=null){
			if(preg_match($matches,$line)){
				return $line;
			} else {
				if($helpText!=null){
					echo $helpText."\n";
				}
				$retries --;
				return $this->readLine($prompt,$matches,$retries,$helpText);
			}
		}
		return $line;
	}
}