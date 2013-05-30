<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Config {
		protected static $_config = array();
		
		public static function write($key, $value) {
			return self::$_config[$key] = $value;
		}
		
		public static function read($key) {
			return self::$_config[$key];
		}
		
		public static function db($key, $environment = 'default') {
			if (!file_exists(APPPATH.DS.'Config'.DS.'Database.php')) {
				die("Can not find Database.php");
			}
			require APPPATH.DS.'Config'.DS.'Database.php';
			return $database[$environment][$key];
		}
	}