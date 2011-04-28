<?php
namespace project\db\om\nanophp;

/**
 * Session class 
 * 
 * Session
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package om
 * @subpackage nanophp
 */
class Session extends \project\db\om\nanophp\base\BaseSession {

	/**
	 * Function - Set Ip
	 * @param mixed $value Value
	 */
	public function setIp($value){
		if(\project\db\om\nanophp\Session::validateIp(ip2long($value))){
			$this->ip = ip2long($value);
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `ip` failed');
		}
	}
	
	/**
	 * Function - Get Ip
	 * @return mixed
	 */
	public function getIp(){
		return long2ip($this->ip);
	}

}