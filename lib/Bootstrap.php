<?php if (!defined('ROOT')) die('No direct script access allowed');

	//Require the core files
	require LIB.'Basics.php';
	require LIB.'Classes'.DS.'Ion.php';

    //Check PHP version before we continue
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        die("Outdated PHP Version, you are running PHP Version: ".PHP_VERSION.". You need to be running 5.4 or greater.");
    }

    //Kill magic quotes
    @magic_quotes_runtime(0);
	
	//set the autoload register to Ion::_load
	spl_autoload_register(['Ion', '_load']);
	
	//Load essential class files
	Ion::load('Network', 'Classes');
	Ion::load('Config', 'Classes');
	Ion::load('View', 'Classes');

    //Set the base Config ini file
    Config::rw("source", APPLIB."Config".DS."source.ini");

    //Start the router, this will start the Main Controller
    Network::route();