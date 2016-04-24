<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

try {

	define('APP_DIR', realpath('../app'));

	/**
	 * Read the configuration
	 */
	include(APP_DIR . '/config/config.php');

	/**
	 * Read auto-loader
	 */
	include(APP_DIR . '/config/loader.php');



	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new \Phalcon\DI\FactoryDefault();

	/**
	 * For use in controllers
	 */	
	$di->setShared('config', $config);
	
	/**
	 * Include the application routes
	 */
	$di->set('router', function() {
		$router = new \Phalcon\Mvc\Router(false);
		include(APP_DIR . '/config/routes.php');
		return $router;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function() use ($config) {
		$url = new \Phalcon\Mvc\Url();
		// Устанавливаем базовый путь
		$url->setBaseUri($config->application->baseUri);
		return $url;
	});

	/**
	 * Setting up the view component
	 */
	$di->set('view', function() use ($config) {
		$view = new \Phalcon\Mvc\View();
		
		// Устанавливаем директорию с шаблонами по умочанию
		$view->setViewsDir($config->application->viewsDir);

		$view->registerEngines(array(
			'.volt' => function($view, $di) use ($config) {
				$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
				$volt->setOptions(array(
					'compiledPath' => $config->application->cacheDir . 'volt/',
					'compiledSeparator' => '_',
				));
				return $volt;
			},
			'.phtml' => 'Phalcon\Mvc\View\Engine\Php' // Generate Template files uses PHP itself as the template engine
		));
		return $view;
	});

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	$di->set('db', function() use ($config) {
		$db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'		=> $config->database->host,
			'username'	=> $config->database->username,
			'password'	=> $config->database->password,
			'dbname' 	=> $config->database->name,
			'charset'	=> $config->database->charset
		));
		return $db;
	});

	/*
	 * Poeben' for normal catching 404
	 */
	$di->set('dispatcher', function() {
		$evManager = new \Phalcon\Events\Manager;
		$evManager->attach(
			"dispatch:beforeException",
			function($event, $dispatcher, $exception) {
				switch ($exception->getCode()) {
					case \Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
					case \Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
						$dispatcher->forward(
							array(
								'controller' => 'page',
								'action'     => 'show404',
							)
						);
						return false;
				}
			}
		);
		$dispatcher = new \Phalcon\Mvc\Dispatcher();
		$dispatcher->setEventsManager($evManager);
		return $dispatcher;
	});

	/**
	 * Request
	 */
	$di->set('request', function() {
		return new \Phalcon\Http\Request;
	});

	/**
	 * Register the flash service with custom CSS classes
	 */
	$di->set('flash', function() {
		return new \Phalcon\Flash\Direct(array(
			'error' => 'alert error',
			'success' => 'alert success',
			'notice' => 'alert info',
		));
	});
	/* And Session Flas */
	$di->set('flashSession', function() {
		return new \Phalcon\Flash\Session(array(
			'error' => 'alert error',
			'success' => 'alert success',
			'notice' => 'alert info',
		));
	});

	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function() {
		$session = new \Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});

	/**
	 * Create a filter
	 */
	$di->set('filter', function() {
		return new \Phalcon\Filter();
	});
	
	/**
	 * New Tag
	 */	
	$di->set('tag', function() {
		return new \Phalcon\NTag();
	});

	/**
	 * Open Graph
	 */	
	$di->set('og', function() {
		return new \Phalcon\OpenGraph();
	});

	Phalcon\Mvc\Model::setup(array(
	    'notNullValidations' => false
	));


	/**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
}