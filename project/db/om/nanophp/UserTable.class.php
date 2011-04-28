<?php
namespace project\db\om\nanophp;

/**
 * UserTable class 
 * 
 * UserTable
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package om
 * @subpackage nanophp
 */
class UserTable extends \project\db\om\nanophp\base\BaseUserTable {

	/**
	 * Function - Retrieve By Pk
	 * @param mixed $id Id
	 * @return mixed
	 */
	public function retrieveByPk($id) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Users')->where('`Users`.`id` = "'.addslashes($id).'" LIMIT 1');
		$results = $this->doSelect($query,'user-'.$id);
		return isset($results[0]['User'])? $results[0]['User'] : null;
	}

	/**
	 * Function - Retrieve By Email
	 * @param mixed $email Email
	 * @return mixed
	 */
	public function retrieveByEmail($email) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Users')->where('`Users`.`email` = "'.addslashes($email).'" LIMIT 1');
		$results = $this->doSelect($query);
		return isset($results[0]['User'])? $results[0]['User'] : null;
	}

	/**
	 * Function - Retrieve By Email And Password
	 * @param mixed $email Email
	 * @param mixed $password Password
	 * @return mixed
	 */
	public function retrieveByEmailAndPassword($email,$password) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Users')->where('`Users`.`is_active` = 1 AND `Users`.`password` = "'.addslashes($password).'" AND `Users`.`email` = "'.addslashes($email).'" LIMIT 1');
		$results = $this->doSelect($query);
		return isset($results[0]['User'])? $results[0]['User'] : null;
	}
	
}