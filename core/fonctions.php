<?php

	/**
	* Fonction de debuggage, donne les infos de @var $var et où elle a été appelée
	**/
	function debug($var)
	{
		$debug = debug_backtrace();
		echo '<p></p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>' . $debug[0]['file'] . '</strong> (line ' . $debug[0]['line'] . ')</a></p>';
		echo '<ol style="display:none">';
		foreach($debug as $k=>$v)
		{
			if($k > 1)
				echo '<li><strong>' . $v['file'] . '</strong> (line ' . $v['line'] . ')</li>';
		}
		echo '</ol>';
		echo '<pre>'; print_r($var); echo '</pre>';
	}