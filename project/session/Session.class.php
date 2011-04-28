<?php
namespace project\session;

class Session extends \nano\core\session\Session {
	
	protected static function getPasswordHash($password) {
		return $password;
	}
	
	public static function login($email,$password,$expires=0) {
		$user = \nano\core\db\ORM::getInstance()->getTable('User','nanophp')->retrieveByEmailAndPassword($email,self::getPasswordHash($password));
		if($user==null){
			return false;
		} else {
			$session = \nano\core\db\ORM::getInstance()->getTable('Session','nanophp')->retrieveByUser($user->getId());
			if($session==null){
				//no session has been found, so make one
				try{
					$session = new \project\db\om\nanophp\Session();
					$session->setUser($user->getId());
					$session->setToken(self::generateNewToken());
					$session->setIp($_SERVER['REMOTE_ADDR']);
					$session->setCreated('MYSQL_NOW()');
					$session->save();
				} catch (\Exception $e) {
					//do nothing - just log
				}
			}
			self::setAttribute('user-id',$user->getId(),$expires);
			self::setAttribute('user-token',$session->getToken(),$expires);
			self::$user = $user;
			return true;
		}
	}
	
	public static function logout($redirect=true) {
		self::$user = null;
		self::setAttribute('user-id','',-3600);
		self::setAttribute('user-token','',-3600);
		if($redirect){
			\nano\core\routing\Routing::getInstance()->getResponse()->pageRedirect('/login');
		}
	}
	
	public static function authenticate() {
		if(self::isSession()) {
			return true;
		} else {
			//attempt to authenticate
			try{
				$session = \nano\core\db\ORM::getInstance()->getTable('Session','nanophp')->retrieveByUserAndToken(self::getAttribute('user-id'),self::getAttribute('user-token'));
				if($session==null){
					return false;
				} else {
					self::$user = $session->getUser();
					self::$isSession = true;
					return true;
				}
			} catch (\Exception $e) {
				
			}
		}
		return false;
	}
	
	public static function isAdmin() {
		if(self::authenticate()) {			
			if(self::$user->getIsAdmin()=='1') {
				return true;
			}
		}
		return false;
	}
	
	public static function isSuperAdmin() {
		if(self::authenticate()) {
			if(self::$user->getIsSuperAdmin()=='1') {
				return true;
			}
		}
		return false;
	}
	
}