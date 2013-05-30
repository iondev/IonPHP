<?php

	define('APPPATH', 'app');
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(__FILE__));
	define('LIB', ROOT.DS.'lib'.DS);
	define('APPLIB', ROOT.DS.APPPATH.DS.'lib'.DS);
	define('PLUGIN', ROOT.DS.'Plugins'.DS);
	
	require LIB.'Bootstrap.php';