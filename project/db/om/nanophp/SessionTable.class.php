<?php
namespace project\db\om\nanophp;

/**
 * SessionTable class 
 * 
 * SessionTable
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package om
 * @subpackage nanophp
 */
class SessionTable extends \project\db\om\nanophp\base\BaseSessionTable {
	
	/**
	 * Function - Retrieve By User
	 * @param mixed $user User
	 * @return mixed
	 */
	public function retrieveByUser($user) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Sessions')->where('`Sessions`.`User` = "'.addslashes($user).'" LIMIT 1');
		$results = $this->doSelect($query,'session-'.$user);
		return isset($results[0]['Session'])? $results[0]['Session'] : null;
	}
	
	/**
	 * Function - Retrieve By User And Token
	 * @param mixed $user User
	 * @param mixed $token Token
	 * @return mixed
	 */
	public function retrieveByUserAndToken($user,$token) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Sessions')->where('`Sessions`.`token` = "'.addslashes($token).'" AND `Sessions`.`User` = "'.addslashes($user).'" LIMIT 1');
		$results = $this->doSelect($query,'session-'.$user.'-'.$token);
		return isset($results[0]['Session'])? $results[0]['Session'] : null;
	}

}