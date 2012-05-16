<?php

	$timeStart = microtime(true);

	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(__FILE__));
	define('CORE', ROOT . DS . 'core');
	define('WEBROOT', ROOT . DS . 'webroot');

	require_once(CORE . DS . 'includes.php');
	
	new Dispatcher(str_replace('/~bourgiem/projet/', '', $_SERVER['REQUEST_URI']));

	if(Conf::$debug)
		echo '<br><br><br><div class="alert-message success fade in" data-alert="alert" style="position:fixed;bottom:0;left:0;right:0;margin:10px;"><a href="#" class="close">&times;</a><p>Page généré en ' . round(microtime(true) - $timeStart, 5) . ' secondes</p></div>';
