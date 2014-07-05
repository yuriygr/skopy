<?php

return new \Phalcon\Config(array(
	'site' => array(
		// Мета данные
		'title'			=> 'Пример блог',
		'description'	=> 'Тестовый блог',
		'keywords'		=> 'Блог',

		// Значения для шапки сайта и подвала
		'name'  	=> 'Иван Иванов',
		'slogan'	=> 'by lina',

		// В разработке
		'postLimit'		=> '9',
		'timeFormat'	=> 'j # Y @ H:i', // % - День недели на русском # - Месяц на русском
	),	
	'database' => array(
		'adapter'	=> 'Mysql',
		'host'		=> 'localhost',
		'username'	=> 'login',
		'password'	=> 'password',
		'name'		=> 'basename',
	),
	'application' => array(
		'controllersDir'	=> __DIR__ . '/../../app/controllers/',
		'modelsDir'			=> __DIR__ . '/../../app/models/',
		'viewsDir'			=> __DIR__ . '/../../app/views/',
		'libraryDir'		=> __DIR__ . '/../../app/library/',
		'baseUri'			=> '/',
		'cryptSalt'			=> 'eEA_&G&f,+v]:A&AFAdsa+71My|:+.u>/6m,$D',
	),
));
