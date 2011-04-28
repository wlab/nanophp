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
 * BaseAuthTable class 
 * 
 * BaseAuthTable
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package nanophp
 * @subpackage base
 */
class BaseAuthTable extends \nano\core\db\om\BaseTable {

	protected $modelName = 'Auth';
	protected $primaryKey = array('id');
	protected $dbConfig = 'default';
	protected $dbName = 'nanophp';
	protected $tableName = 'Auths';
	protected $fields = array(
		'id' => array(
			'mysql_type' => 'int(11)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'PRI',
			'mysql_default' => '',
			'mysql_extra' => 'auto_increment',
			'is_foreign_reference' => false,
			'use_model' => 'Auth',
			'use_database' => 'nanophp',
			'set_function' => 'setId',
			'validation_function' => 'validateId',
			'get_function' => 'getId',
		),
		'type' => array(
			'mysql_type' => 'varchar(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Auth',
			'use_database' => 'nanophp',
			'set_function' => 'setType',
			'validation_function' => 'validateType',
			'get_function' => 'getType',
		),
		'uid' => array(
			'mysql_type' => 'int(11)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Auth',
			'use_database' => 'nanophp',
			'set_function' => 'setUid',
			'validation_function' => 'validateUid',
			'get_function' => 'getUid',
		),
		'fuid' => array(
			'mysql_type' => 'char(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => 'MUL',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Auth',
			'use_database' => 'nanophp',
			'set_function' => 'setFuid',
			'validation_function' => 'validateFuid',
			'get_function' => 'getFuid',
		),
		'perams' => array(
			'mysql_type' => 'char(255)',
			'mysql_is_null' => 'NO',
			'mysql_key' => '',
			'mysql_default' => '',
			'mysql_extra' => '',
			'is_foreign_reference' => false,
			'use_model' => 'Auth',
			'use_database' => 'nanophp',
			'set_function' => 'setPerams',
			'validation_function' => 'validatePerams',
			'get_function' => 'getPerams',
		)
	);
	protected $newFieldNameMap = array(
		'id' => 'id',
		'type' => 'type',
		'uid' => 'uid',
		'fuid' => 'fuid',
		'perams' => 'perams'
	);

	public function retrieveByPk($id) {
		$query = new \nano\core\db\core\SelectQuery();
		$query->from('Auths')->where('`Auths`.`id` = "'.addslashes($id).'" LIMIT 1');
		$results = $this->doSelect($query);
		return isset($results[0]['Auth'])? $results[0]['Auth'] : null;
	}

}