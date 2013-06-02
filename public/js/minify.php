<?php

	header('Content-type: text/javascript; charset=UTF-8');
	ob_start("compress_js");
	function compress_js($buffer) {
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $buffer);
		return $buffer;
	}

	//detect IE 8
	if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) {
	    // if IE<=8
	    include ('html5shiv.js');
	}

	include ('skel-ui.min.js');
	include ('vendor/modernizr-2.6.2.min.js');

	ob_end_flush();