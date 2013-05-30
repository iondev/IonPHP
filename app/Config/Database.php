<?php if (!defined('ROOT')) die('No direct script access allowed');

	$database = array(
		'default' => array(
			'host' => '127.0.0.1',
			'port' => '3360',
			'user' => 'root',
			'pass' => ''
		),	
	);
	
	return array('database' => $database);