<?php
/**
 * @package     Skopy
 *
 * @copyright   Copyright (C) 2005 - 2017 Yuriy Grinev. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
try {
	/**
	 * Show all error
	 */
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	/**
	 * Timezone
	 */
	date_default_timezone_set('Europe/Moscow');

	/**
	 * Define App directory
	 */
	define('BASE_DIR', realpath('../'));
	define('APP_DIR', realpath('../app'));
	define('PUB_DIR', realpath('../public'));

	/**
	 * Load vendors from composer
	 */
	include_once(APP_DIR . '/vendor/autoload.php');

	/**
	 * Read environment 
	 */
	include_once(APP_DIR . '/config/environment.php');

	/**
	 * Read the configuration
	 */
	include_once(APP_DIR . '/config/config.php');

	/**
	 * Read auto-loader
	 */
	include_once(APP_DIR . '/config/loader.php');

	/**
	 * Read services
	 */
	include_once(APP_DIR . '/config/services.php');

	/**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application($di);
	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
}