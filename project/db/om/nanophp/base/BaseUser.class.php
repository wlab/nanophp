<?php
/*************************************************************************************************************************
**************************************************************************************************************************
**************************************************************************************************************************

WARNING!!!

THIS FILE SHOULD NOT BE EDITED - IT WILL BE AUTOGENERATED EACH TIME THE MODELS ARE BUILT!

OVERRIDE SETTINGS IN MODEL NOT THE BASEMODEL

**************************************************************************************************************************
**************************************************************************************************************************
*************************************************************************************************************************/

namespace project\db\om\nanophp\base;

/**
 * BaseUser class 
 * 
 * BaseUser
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package nanophp
 * @subpackage base
 */
class BaseUser extends \nano\core\db\om\Base {

	protected $modelName = 'User';
	protected $primaryKey = array('id');
	protected $dbConfig = 'default';
	protected $dbName = 'nanophp';
	protected $tableName = 'Users';
	protected $fields = array(
		'id' => array(
			'mysql_type' => 'int(11)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'PRI',
			'mysql_default' => '',
			'mysql_extra' => 'auto_increment',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setId',
			'validation_function' => 'validateId',
			'get_function' => 'getId',
		),
		'language' => array(
			'mysql_type' => 'char(7)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setLanguage',
			'validation_function' => 'validateLanguage',
			'get_function' => 'getLanguage',
		),
		'email' => array(
			'mysql_type' => 'varchar(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'MUL',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setEmail',
			'validation_function' => 'validateEmail',
			'get_function' => 'getEmail',
		),
		'password' => array(
			'mysql_type' => 'varchar(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setPassword',
			'validation_function' => 'validatePassword',
			'get_function' => 'getPassword',
		),
		'is_active' => array(
			'mysql_type' => 'tinyint(1)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '0',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setIsActive',
			'validation_function' => 'validateIsActive',
			'get_function' => 'getIsActive',
		),
		'is_admin' => array(
			'mysql_type' => 'tinyint(1)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '0',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setIsAdmin',
			'validation_function' => 'validateIsAdmin',
			'get_function' => 'getIsAdmin',
		),
		'is_super_admin' => array(
			'mysql_type' => 'tinyint(1)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '0',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setIsSuperAdmin',
			'validation_function' => 'validateIsSuperAdmin',
			'get_function' => 'getIsSuperAdmin',
		),
		'created_at' => array(
			'mysql_type' => 'datetime',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setCreatedAt',
			'validation_function' => 'validateCreatedAt',
			'get_function' => 'getCreatedAt',
		),
		'updated_at' => array(
			'mysql_type' => 'datetime',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'User',
			'use_database' => 'nanophp',
			'set_function' => 'setUpdatedAt',
			'validation_function' => 'validateUpdatedAt',
			'get_function' => 'getUpdatedAt',
		)
	);
	protected $newFieldNameMap = array(
		'id' => 'id',
		'language' => 'language',
		'email' => 'email',
		'password' => 'password',
		'is_active' => 'is_active',
		'is_admin' => 'is_admin',
		'is_super_admin' => 'is_super_admin',
		'created_at' => 'created_at',
		'updated_at' => 'updated_at'
	);
	public function setId($value){
		if(\project\db\om\nanophp\User::validateId($value)){
			$this->id = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `id` failed');
		}
	}
	public function setLanguage($value){
		if(\project\db\om\nanophp\User::validateLanguage($value)){
			$this->language = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `language` failed');
		}
	}
	public function setEmail($value){
		if(\project\db\om\nanophp\User::validateEmail($value)){
			$this->email = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `email` failed');
		}
	}
	public function setPassword($value){
		if(\project\db\om\nanophp\User::validatePassword($value)){
			$this->password = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `password` failed');
		}
	}
	public function setIsActive($value){
		if(\project\db\om\nanophp\User::validateIsActive($value)){
			$this->is_active = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `is_active` failed');
		}
	}
	public function setIsAdmin($value){
		if(\project\db\om\nanophp\User::validateIsAdmin($value)){
			$this->is_admin = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `is_admin` failed');
		}
	}
	public function setIsSuperAdmin($value){
		if(\project\db\om\nanophp\User::validateIsSuperAdmin($value)){
			$this->is_super_admin = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `is_super_admin` failed');
		}
	}
	public function setCreatedAt($value){
		if(\project\db\om\nanophp\User::validateCreatedAt($value)){
			$this->created_at = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `created_at` failed');
		}
	}
	public function setUpdatedAt($value){
		if(\project\db\om\nanophp\User::validateUpdatedAt($value)){
			$this->updated_at = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `updated_at` failed');
		}
	}
	public function getId(){
		return $this->id;
	}
	public function getLanguage(){
		return $this->language;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getIsActive(){
		return $this->is_active;
	}
	public function getIsAdmin(){
		return $this->is_admin;
	}
	public function getIsSuperAdmin(){
		return $this->is_super_admin;
	}
	public function getCreatedAt(){
		return $this->created_at;
	}
	public function getUpdatedAt(){
		return $this->updated_at;
	}
	public static function validateId($value){
		return true;
	}
	public static function validateLanguage($value){
		return true;
	}
	public static function validateEmail($value){
		return true;
	}
	public static function validatePassword($value){
		return true;
	}
	public static function validateIsActive($value){
		return true;
	}
	public static function validateIsAdmin($value){
		return true;
	}
	public static function validateIsSuperAdmin($value){
		return true;
	}
	public static function validateCreatedAt($value){
		return true;
	}
	public static function validateUpdatedAt($value){
		return true;
	}
	
}