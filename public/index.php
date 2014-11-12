<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

try {

	/**
	 * Read the configuration
	 */
	$config = include(__DIR__.'/../app/config/config.php');

	$loader = new \Phalcon\Loader();

	/**
	 * We're a registering a set of directories taken from the configuration file. And namespaces too
	 */
	$loader->registerDirs(
		array(
			$config->application->controllersDir,
			$config->application->modelsDir,
			$config->application->libraryDir,
		)
	);
	$loader->registerNamespaces(
		array(
			'Phalcon' => $config->application->libraryDir
		)
	);
	$loader->register();


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
		$router = new \Phalcon\Mvc\Router();
		include(__DIR__.'/../app/config/routes.php');
		return $router;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function() use ($config) {
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri($config->application->baseUri);
		return $url;
	});

	/**
	 * Setting up the view component
	 */
	$di->set('view', function() use ($config) {
		$view = new \Phalcon\Mvc\View();
		$view->setViewsDir($config->application->viewsDir);
		return $view;
	});

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	$di->set('db', function() use ($config) {
		return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'		=> $config->database->host,
			'username'	=> $config->database->username,
			'password'	=> $config->database->password,
			'dbname' 	=> $config->database->name,
			'options'	=> array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			)
		));
	});

	/*
	 * Poeben' for normal catching 404
	 */
	$di->set('dispatcher', function() {
		//Create an EventsManager
		$eventsManager = new \Phalcon\Events\Manager();
		//Attach a listener
		$eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {
			//Handle 404 exceptions
			if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
				$dispatcher->forward(array(
					'controller' => 'pages',
					'action' => 'show404'
				));
				return false;
			}
			//Alternative way, controller or action doesn't exist
			if ($event->getType() == 'notFoundAction') {
				switch ($exception->getCode()) {
					case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
					case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
						$dispatcher->forward(array(
							'controller' => 'pages',
							'action' => 'show404'
						));
					return false;
				}
			}
		});
		$dispatcher = new \Phalcon\Mvc\Dispatcher();
		//Bind the EventsManager to the dispatcher
		$dispatcher->setEventsManager($eventsManager);
		return $dispatcher;
	}, true);


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