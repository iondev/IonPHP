<?php

	header('Content-type: text/css');
	ob_start("compress");
	function compress($buffer) {
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $buffer);
		return $buffer;
	}

	include ('style.css');
	include ('style-wide.css');