<?php
namespace project\config\environments;

class Dev {
	public static $config = array(
		/* jQuery Settings */
		'jquery_theme' => 'nanophp', /** Theme */
		
		/* Twig Settings - See Twig documentation for details. */
		'twig_debug' => true,
		'twig_auto_reload' => true,
		
		/* Internationalization Settings */
		'i18n_populate' => true, /* If true, any i18n values specified in the i18n() call will be added to the database if they do not exist. */
		'i18n_overwrite' => false, /* If true, any i18n values specified in the i18n() call will replace existing values in the database. */
		'i18n_marker' => false, /* If true, {{ double braces }} will be placed around any text returned by the i18n() function. */
		
		/* Log Settings */
		'logging_bar' => true, /* Show the logging bar at the top of the page for development purposes. */
		'logging_priority' => \nano\core\log\Log::PRIORITY_LEVEL_LOGFILE, /* Default priority level for logging errors. Can be changed in individual exception classes for added control */
		'logging_send_to' => array(
			/* Errors with priority PRIORITY_LEVEL_EMAIL will be sent to all addresses in this array. */
			'yourname@yourdomain.com',
		),
		
		/* Database Settings */
		'databases' => array (
			/* 'default' database configuration must exist; others may be added below. */
			'default' => array(
				'mode' => \nano\core\db\core\Database::DB_MODE_SINGLE, /* Database access mode. */
				'name' => 'nanophp', /* MySQL database name. */
				'servers' => array(
					array(
						'host' => 'localhost', /* Database hostname/IP address. */
						'port' => '3306', /* Database port. UNIX sockets are not supported at this time. */
						'user' => 'root', /* MySQL username. */
						'pass' => 'test' /* MySQL password. */
					)
				)
			)
		),
		
		/* Memcache Settings */
		'caching' => true, /* Set to TRUE to enable the cache. The PHP 'Memcached' extension, and memcached itself, must be installed. */
		'memcache_servers' => array(
			/* Array of memcached servers in the form 'hostname' => 'port'. */
			'default' => array(
				'127.0.0.1' => '11211'
			),
		),
		
		/* Configure PHP's error handling functionality according to one of the functions defined in PHPErrors.class.php. */
		'errors' => 'dev'
	);
}