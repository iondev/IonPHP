<?php if (!defined('ROOT')) die('No direct script access allowed');

	//Require the core files
	require LIB.'Basics.php';
	require LIB.'Classes'.DS.'Ion.php';
	
	//Gather the full URL
	if (!defined('FULL_URL')) {
		$s = null;
		if (env('HTTPS')) {
			$s = 's';
		}
		$httpHost = env('HTTP_HOST');
		if (isset($httpHost)) {
			define('FULL_URL', 'http'.$s.'://'.$httpHost);
		}
		unset($httpHost, $s);
	}
	
	//set the autoload register to Ion::_load
	spl_autoload_register(array('Ion', '_load'));
	
	//Load essential class files
	Ion::load('Network', 'Classes');
	Ion::load('Config', 'Classes');
	Ion::load('View', 'Classes');

    Network::route();