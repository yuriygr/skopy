<?php

$config =  new \Phalcon\Config(array(
	'site' => array(
		'favicon'			=> '/favicon.ico',
		'title'				=> 'Иван Иванов',
		'description'		=> 'Личный блог Ивана.',
		'keywords'			=> 'Иван, Иванов, Блог, Skopy',
		'link'				=> 'http://localhost',

		'postLimit'			=> '10',
		'disqusShortname' 	=> '',
	),
	'contacts' => array(
		'email'				=> '',
		'telegram'			=> '',
		'vk'				=> '',
	),	
	'database' => array(
		'adapter'			=> 'Mysql',
		'host'				=> 'localhost',
		'username'			=> 'USERNAME',
		'password'			=> 'PASSWORD',
		'name'				=> 'BASENAME',
		'charset'			=> 'utf8',
	),
	'application' => array(
		'controllersDir'	=> APP_DIR . '/controllers/',
		'modelsDir'			=> APP_DIR . '/models/',
		'viewsDir'			=> APP_DIR . '/views/',
		'libraryDir'		=> APP_DIR . '/library/',
		'cacheDir'			=> APP_DIR . '/cache/',
		'baseUri'			=> '/',
		'cryptSalt'			=> 'eEA_&G&f,+v]:A&+71My|:+.u>/6m,$D',
	),
));
