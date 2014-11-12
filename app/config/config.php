<?php

return new \Phalcon\Config(array(
	'site' => array(
		// Мета данные
		'title'			=> 'Иван Иванов',
		'description'	=> 'Личный бложик Ивана',
		'keywords'		=> 'Блог, Иван',

		'postLimit'		=> '9',
	),	
	'database' => array(
		'adapter'	=> 'Mysql',
		'host'		=> 'HOST',
		'username'	=> 'USERNAME',
		'password'	=> 'PASSWORD',
		'name'		=> 'blog',
	),
	'application' => array(
		'controllersDir'	=> __DIR__ . '/../../app/controllers/',
		'modelsDir'			=> __DIR__ . '/../../app/models/',
		'viewsDir'			=> __DIR__ . '/../../app/views/',
		'libraryDir'		=> __DIR__ . '/../../app/library/',
		'baseUri'			=> '/',
		'cryptSalt'			=> 'eEA_&G&f,+v]:A&+71My|:+.u>/6m,$D',
	),
));
