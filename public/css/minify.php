<?php

	header('Content-type: text/css');
	ob_start("compress_css");
	function compress_css($buffer) {
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $buffer);
		return $buffer;
	}

	include ('style.css');
	include ('style-wide.css');

	ob_end_flush();