<?php
namespace project\apps\back_end;

/**
 * Routing class 
 * 
 * Routing
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package projects
 * @subpackage back_end
 */
class Routing {
	
	public static $routing = array(
		'index_page' => array
		(
			'url' => '[/]?',
			'url_for' => '/',
			'class' => 'project\apps\back_end\pages\index\Index'
		),
		'login_page' => array
		(
			'url' => '/login(?:/(?<reason>[^/]+))?',
			'class' => 'project\apps\back_end\pages\login\Login'
		),
		'logout_page' => array
		(
			'url' => '/logout',
			'class' => 'project\apps\back_end\pages\logout\Logout'
		),
		'list_view_page' => array
		(
			'url' => '/:database/:model/list',
			'class' => 'project\apps\back_end\pages\list_view\ListView'
		),
		'view_page' => array
		(
			'url' => '/:database/:model/view',
			'class' => 'project\apps\back_end\widgets\view\View'
		),
		'edit_page' => array
		(
			'url' => '/:database/:model/edit',
			'class' => 'project\apps\back_end\pages\edit\Edit'
		),
		'add_page' => array
		(
			'url' => '/:database/:model/add',
			'class' => 'project\apps\back_end\pages\add\Add',
		),
		'model_export_page' => array
		(
			'url' => '/:database/:model/export',
			'class' => 'project\apps\back_end\pages\export\ModelExport'
		),
		'model_export_as_type_page' => array
		(
			'url' => '/:database/:model/export-as-(?<type>csv)',
			'class' => 'project\apps\back_end\widgets\export\ModelExport'
		),
		'model_import_page' => array
		(
			'url' => '/:database/:model/import',
			'class' => 'project\apps\back_end\pages\import\ModelImport'
		),
	);
	
}