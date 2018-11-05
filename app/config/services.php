<?php

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
$di->set('router', function() use ($config) {
	$router = new \Phalcon\Mvc\Router(false);
	include($config->application->configDir . '/routes.php');
	return $router;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
	$url = new \Phalcon\Mvc\Url();

	// Set base Uri
	$url->setBaseUri($config->application->baseUri);
	return $url;
});

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {
	$view = new \Phalcon\Mvc\View();

	$view->setViewsDir($config->application->viewsDir);
	$view->registerEngines([
		'.volt' => function($view, $di) use ($config) {
			$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
			$volt->setOptions([
				'compiledPath' => $config->application->cacheDir . 'volt/',
				'compiledSeparator' => '_',
			]);
			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	]);

	return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 * See more adapters: https://github.com/phalcon/incubator/tree/master/Library/Phalcon/Db/Adapter
 */
$di->setShared('db', function() use ($config) {
	return new \Phalcon\Db\Adapter\Pdo\Mysql([
		'host' 			=> $config->database->host,
		'username' 		=> $config->database->username,
		'password' 		=> $config->database->password,
		'dbname' 		=> $config->database->name,
		'charset' 		=> $config->database->charset,
		'persistent' 	=> $config->database->persistent,
		'options' 		=> [
			\PDO::ATTR_ERRMODE 		=> \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_PERSISTENT 	=> true,
			\PDO::ATTR_AUTOCOMMIT 	=> false
		]
	]);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 * See more adapters: https://github.com/phalcon/incubator/tree/master/Library/Phalcon/Mvc/Model/MetaData
 */
$di->set('modelsMetadata', function () use ($config) {
	return new \Phalcon\Mvc\Model\Metadata\Redis([
		'host' 			=> $config->redis->host,
		'port' 			=> $config->redis->port,
		'persistent' 	=> $config->redis->persistent,
		'statsKey' 		=> '_PHCM_MM',
		'lifetime' 		=> $config->redis->lifetime
	]);
});

/**
 * Set the models cache service
 */
$di->set('modelsCache', function () use ($config) {

	// Cache data for one day by default
	$frontCache = new \Phalcon\Cache\Frontend\Data([
		'lifetime' => $config->redis->lifetime,
	]);

	// Memcached connection settings
	$cache = new \Phalcon\Cache\Backend\Redis($frontCache, [
		'host' 			=> $config->redis->host,
		'port' 			=> $config->redis->port,
		'index' 		=> $config->redis->index,
		'persistent' 	=> $config->redis->persistent
	]);

	return $cache;
});

/*
 * Dispatcher
 */
$di->set('dispatcher', function() {
	$eventsManager = new \Phalcon\Events\Manager();

	// ACL
	$eventsManager->attach("dispatch:beforeDispatch", new SecurityPlugin);
	// Handles not-found controller/actions
	$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

	$dispatcher = new \Phalcon\Mvc\Dispatcher();
	$dispatcher->setEventsManager($eventsManager);
	return $dispatcher;
});


/**
 * Start the session the first time some component request the session service
 * See more adapters: https://github.com/phalcon/incubator/tree/master/Library/Phalcon/Session/Adapter
 */
$di->set('session', function() use ($config) {
	$session = new \Phalcon\Session\Adapter\Redis([
		'host' 		=> $config->redis->host,
		'port' 		=> $config->redis->port,
		'lifetime' 	=> $config->redis->lifetime,
	]);
	$session->start();
	return $session;
});

/**
 * Request
 */
$di->set('request', function() {
	return new \Phalcon\Http\Request;
});

/**
 * Assets manager and default settings
 */
$di->set('assets', function() {
	$assets = new \Phalcon\NewAssets();
	
	// JS
	$assets
		 ->collection('app-js')
		 ->addJs('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', false, false)
		 ->addJs('js/jquery.ambiance.js')
		 ->addJs('js/main.js');

	// Fonts
	$assets
		 ->collection('app-fonts')
		 ->addCss('//fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin,cyrillic-ext', false, false);

	// CSS
	$assets
		 ->collection('app-css')
		 ->addCss('//cdnjs.cloudflare.com/ajax/libs/normalize/6.0.0/normalize.min.css', false, false)
		 ->addCss('css/style.css');

	return $assets;
});

/**
 * New Tag
 */
$di->set('tag', function() use ($config) {
	$tag = new \Phalcon\NewTag();

	$tag->setAutoescape(false);
	$tag->setDocType($tag::HTML5);
	$tag->setFavicon($config->site->favicon);
	$tag->setTitleSeparator(' - ');

	$tag->setTitle($config->site->title);
	$tag->setDescription($config->site->description);
	$tag->setKeywords($config->site->keywords);

	return $tag;
});

/**
 * Create a filter
 */
$di->set('filter', function() {
	return new \Phalcon\Filter();
});

/**
 * Escape
 */
$di->set('escaper', function() {
	return new \Phalcon\Escaper();
});

/**
 * Create Authorization
 */
$di->set('auth', function() {
	return new \Phalcon\Authorization();
});

/**
 * Open Graph
 */
$di->set('opengraph', function() {
	return new \Phalcon\OpenGraph();
});

Phalcon\Mvc\Model::setup([
	'notNullValidations' => false
]);