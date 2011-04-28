<?php
namespace project\apps\back_end\pages\login;

/**
 * Login class 
 * 
 * Login
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk> & Alessandro Barzanti <alessandro.barzanti@jpmh.co.uk>
 * @version SVN: $id
 * @package pages
 * @subpackage login
 */
class Login extends \project\apps\back_end\templates\PageTemplate {

	/**
	 * Function - Pre Load
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	protected function preLoad(\nano\core\routing\Routing $routing){
		//over-ride parent
	}

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 */
	public function execute(\nano\core\routing\Routing $routing){
		
		$this->headerWidget->setTitle(i18n('admin.pagetitle.login','Admin - Login Page'));
		
		if(isset($_GET['reason'])){
			switch($_GET['reason']){
				case 'super-admin-required':
					$this->errorMessage = i18n('admin.login.error.super-admin-required','Super Admin Permissions Required');
					break;
				case 'admin-required':
					$this->errorMessage = i18n('admin.login.error.admin-required','Admin Permissions Required');
					break;
			}
		}
		
		if(\project\session\Session::isAdmin()){
			$routing->getResponse()->pageRedirect('/');
		}
		
		if(!empty($_POST)){
			if(\project\session\Session::login($_POST['username'],$_POST['password'])){
				$routing->getResponse()->pageRedirect('/');
			} else {
				$this->errorMessage = i18n('admin.login.error.incorrect-username-or-password','Incorrect Username or Password');
			}
		}
		
		return 'project/apps/back_end/pages/login/views/login.twig';
	}
	
}