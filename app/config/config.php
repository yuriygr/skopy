<?php

$config = new \Phalcon\Config([
	'site' => [
		'favicon'			=> '/img/favicon.ico',
		'title'				=> getenv('SITE_NAME'),
		'description'		=> getenv('SITE_DESCRIPTION'),
		'keywords'			=> getenv('SITE_KEYWORDS'),
		'link'				=> getenv('SITE_LINK'),
		'postLimit'			=> getenv('SITE_POST_LIMIT')
	],
	'database' => [
		'host' 				=> getenv('DATABASE_HOST'),
		'name' 				=> getenv('DATABASE_NAME'),
		'username' 			=> getenv('DATABASE_USER'),
		'password' 			=> getenv('DATABASE_PASS'),
		'charset' 			=> getenv('DATABASE_CHARSET'),
		'persistent'		=> getenv('DATABASE_PERSISTENT')
	],
	'redis' => [
		'host'				=> getenv('REDIS_HOST'),
		'port'				=> getenv('REDIS_PORT'),
		'lifetime'			=> getenv('REDIS_LIFETIME'),
		'index'				=> getenv('REDIS_INDEX'),
		'persistent'		=> getenv('REDIS_PERSISTENT')
	],
	'application' => [
		'baseUri'			=> '/',
		'version' 			=> '1.5.6',
		'offline'			=> getenv('APP_OFFLINE'),
		'debug'				=> getenv('APP_DEBUG'),
		'ga_id'				=> getenv('GOOGLE_ANALYTICS'),
		'log_lvl'			=> getenv('APP_LOG_LVL'),
		'cryptSalt'			=> 'eEA_&G&f,+v]:A&+71My|:+.u>/6m,$D',
		// Folders path
		'configDir'			=> APP_DIR  . '/config/',
		'controllersDir'	=> APP_DIR  . '/controllers/',
		'modelsDir'			=> APP_DIR  . '/models/',
		'viewsDir'			=> APP_DIR  . '/views/',
		'libraryDir'		=> APP_DIR  . '/library/',
		'pluginsDir'		=> APP_DIR  . '/plugins/',
		'cacheDir'			=> BASE_DIR . '/cache/',
	],
]);
