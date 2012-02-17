<?php
namespace project\apps\example;

/**
 * Routing class 
 * 
 * Routing
 * 
 * @author Christopher Beck <cwbeck@gmail.com>
 * @version SVN: $id
 * @package projects
 * @subpackage example
 */
class Routing {
	
	public static $routing = array(
		/*
			ROUTING FOR PROJECT 'example'
		*/
		// 'example_page' => array // 'example_page' - this should be a unique name. Use prefixes :)
		// (
		// 	'url' => '/example_page', //This is the url to match, must start with '/' | Slugs = /:bla/ | Otherwise regex format
		// 	//Slugs appear in the $_GET global var.
		// 	'requirements' => array( //Matches the slugs provided in the URL (regex format) (NOT REQUIRED ATTRIBUTE)
		// 		'id' => '\d+',
		// 		'type' => '\w+'
		// 	),
		// 	'class' => 'project\apps\example\Example' //The class to load if a match... Will not continue to next rule.
		// )
		'index_page' => array // 'example_page' - this should be a unique name. Use prefixes :)
		(
			'url' => '[/]?',
			'class' => 'project\apps\example\pages\index\Index' //The class to load if a match... Will not continue to next rule.
		)
	);
}