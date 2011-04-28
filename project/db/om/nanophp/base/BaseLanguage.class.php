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
 * BaseLanguage class 
 * 
 * BaseLanguage
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package nanophp
 * @subpackage base
 */
class BaseLanguage extends \nano\core\db\om\Base {

	protected $modelName = 'Language';
	protected $primaryKey = array('code','key');
	protected $dbConfig = 'default';
	protected $dbName = 'nanophp';
	protected $tableName = 'Languages';
	protected $fields = array(
		'code' => array(
			'mysql_type' => 'char(7)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'PRI',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Language',
			'use_database' => 'nanophp',
			'set_function' => 'setCode',
			'validation_function' => 'validateCode',
			'get_function' => 'getCode',
		),
		'key' => array(
			'mysql_type' => 'char(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'PRI',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Language',
			'use_database' => 'nanophp',
			'set_function' => 'setKey',
			'validation_function' => 'validateKey',
			'get_function' => 'getKey',
		),
		'value' => array(
			'mysql_type' => 'text',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Language',
			'use_database' => 'nanophp',
			'set_function' => 'setValue',
			'validation_function' => 'validateValue',
			'get_function' => 'getValue',
		)
	);
	protected $newFieldNameMap = array(
		'code' => 'code',
		'key' => 'key',
		'value' => 'value'
	);
	public function setCode($value){
		if(\project\db\om\nanophp\Language::validateCode($value)){
			$this->code = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `code` failed');
		}
	}
	public function setKey($value){
		if(\project\db\om\nanophp\Language::validateKey($value)){
			$this->key = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `key` failed');
		}
	}
	public function setValue($value){
		if(\project\db\om\nanophp\Language::validateValue($value)){
			$this->value = $value;
		} else {
			throw new \nano\core\exception\ValidationException('Validation of column `value` failed');
		}
	}
	public function getCode(){
		return $this->code;
	}
	public function getKey(){
		return $this->key;
	}
	public function getValue(){
		return $this->value;
	}
	public static function validateCode($value){
		return true;
	}
	public static function validateKey($value){
		return true;
	}
	public static function validateValue($value){
		return true;
	}
	
}