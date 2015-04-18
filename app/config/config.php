<?php

return new \Phalcon\Config(array(
	'site' => array(
		// Мета данные
		'title'			=> 'Иван Иванов',
		'description'	=> 'Личный блог Ивана',
		'keywords'		=> 'Иван, Иванов, Блог, Skopy',

		'postLimit'		=> '9',
	),	
	'database' => array(
		'adapter'	=> 'Mysql',
		'host'		=> 'localhost',
		'username'	=> 'USERNAME',
		'password'	=> 'PASSWORD',
		'name'		=> 'BASENAME',
	),
	'application' => array(
		'controllersDir'	=> __DIR__ . '/../../app/controllers/',
		'modelsDir'			=> __DIR__ . '/../../app/models/',
		'viewsDir'			=> __DIR__ . '/../../app/views/',
		'libraryDir'		=> __DIR__ . '/../../app/library/',
		'baseUri'			=> '/',
		'cryptSalt'			=> 'eEA_&G&*(^S&*)]:A&+71My|:+.u>/6m,$D',
	),
));
