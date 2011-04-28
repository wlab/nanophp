<?php
namespace project\lib\swift;

/**
 * Autoloader for Swift mailer.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.lib.swift
 */
class Swift {
	
	final private function __construct() {}
	final private function __clone() {}
	
	public static function configure() {
		require_once($_SERVER['SCRIPTS_LOAD_FROM'].'lib/Swift/lib/swift_init.php');
	}
	
}