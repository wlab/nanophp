<?php
namespace project\apps\{{ projectName }};

/**
 * Routing class 
 * 
 * Routing
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 * @package projects
 * @subpackage {{ projectName }}
 */
class Routing {
	
	public static $routing = array(
		/*
			ROUTING FOR PROJECT '{{ projectName }}'
		*/
		// 'example_page' => array // 'example_page' - this should be a unique name. Use prefixes :)
		// (
		// 	'url' => '/example_page', //This is the url to match, must start with '/' | Slugs = /:bla/ | Otherwise regex format
		// 	//Slugs appear in the $_GET global var.
		// 	'requirements' => array( //Matches the slugs provided in the URL (regex format) (NOT REQUIRED ATTRIBUTE)
		// 		'id' => '\d+',
		// 		'type' => '\w+'
		// 	),
		// 	'class' => 'project\apps\{{ projectName }}\Example' //The class to load if a match... Will not continue to next rule.
		// )
		'index_page' => array // 'example_page' - this should be a unique name. Use prefixes :)
		(
			'url' => '[/]?',
			'class' => 'project\apps\{{ projectName }}\pages\index\Index' //The class to load if a match... Will not continue to next rule.
		)
	);
}