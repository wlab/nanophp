<?php
namespace project\db\om\nanophp;

/**
 * AuthTable class 
 * 
 * AuthTable
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package om
 * @subpackage nanophp
 */
class AuthTable extends \project\db\om\nanophp\base\BaseAuthTable {

	public function retrieveByTypeAndUid($type,$uid) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Auths')->where('`Auths`.`type` = "'.addslashes($type).'" AND `Auths`.`uid` = "'.addslashes($uid).'" LIMIT 1');
		$results = $this->doSelect($query);
		return isset($results[0]['Auth'])? $results[0]['Auth'] : null;
	}
	
	public function retrieveByTypeAndFuid($type,$fuid) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Auths')->where('`Auths`.`type` = "'.addslashes($type).'" AND `Auths`.`fuid` = "'.addslashes($fuid).'" LIMIT 1');
		$results = $this->doSelect($query);
		return isset($results[0]['Auth'])? $results[0]['Auth'] : null;
	}

}