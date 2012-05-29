<?php

	header('HTTP/1.1 200 OK');

	$timeStart = microtime(true);

	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(__FILE__));
	define('CORE', ROOT . DS . 'core');
	define('WEBROOT', ROOT . DS . 'webroot');
	define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));
	require_once(CORE . DS . 'includes.php');

	new Dispatcher(str_replace(BASE_URL . DS, '', $_SERVER['REQUEST_URI']));
