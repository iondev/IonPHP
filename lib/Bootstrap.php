<?php if (!defined('ROOT')) die('No direct script access allowed');

    session_start();

	//Require the core files
	require LIB.'Basics.php';
    require LIB.'Classes'.DS.'Autoloader.php';

    //Check PHP version before we continue
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        die("Outdated PHP Version, you are running PHP Version: ".PHP_VERSION.". You need to be running 5.4 or greater.");
    }
	
	//set the autoload register to Ion::_load
	spl_autoload_register(['Autoloader', '_load']);
	
	//Load essential class files
    Autoloader::load('Security');

    //Run base secruity check
    Security::defend();

    Autoloader::load('Config');
    Autoloader::load('Cache');
    Autoloader::load('View');
    Autoloader::load('Model');
    Autoloader::load('Loader');
    Autoloader::load('Controller');
    Autoloader::load('Encryption');
    Autoloader::load('CSRF');

    //Create CSRF token
    CSRF::generate_token();

    //Load URL routing
    Autoloader::load('Network');

    //Set the base Config ini file
    Config::rw("source", LIB."Config".DS."source.ini");

    //Start the router, this will start the Main Controller
    Network::route();

    //We shall load the database class but we wont connect
    Autoloader::load('Database');