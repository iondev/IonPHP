<?php if (!defined('ROOT')) die('No direct script access allowed');

	//Require the core files
	require LIB.'Basics.php';
	require LIB.'Classes'.DS.'Ion.php';
	
	//set the autoload register to Ion::_load
	spl_autoload_register(array('Ion', '_load'));
	
	//Load essential class files
	Ion::load('Network', 'Classes');
	Ion::load('Config', 'Classes');
	Ion::load('View', 'Classes');

    //Set the base Config ini file
    Config::rw("source", APPLIB."Config".DS."source.ini");

    //Start the router, this will start the Main Controller
    Network::route();