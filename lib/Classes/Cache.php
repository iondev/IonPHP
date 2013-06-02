<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Cache {

		protected static $dir = 'Cache';
		protected static $time = 3600;

		private static function get_cache_dir() {
			return APPLIB.self::$dir.DS;
		}

		public static function retrieve_data($label, $url) {
			if ($data = self::get_cache($label)) {
				return $data;
			}else{
				$data = self::do_curl($url);
				self::set_cache($label, $data);
				return $data;
			}
		}

		public static function set_cache($label, $data) {
			file_put_contents(self::get_cache_dir() . self::safe_filename($label), $data);
		}

		public static function get_cache($label) {
			if (self::is_cached($label)) {
				$filename = self::get_cache_dir() . self::safe_filename($label) .'.cache';
				return file_get_contents($filename);
			}
			return false;
		}

		public static function is_cached($label) {
			$filename = self::get_cache_dir() . self::safe_filename($label) .'.cache';
			if(file_exists($filename) && (filemtime($filename) + self::$cache_time >= time())) return true;
			return false;
		}

		public static function do_curl($url) {
			if (function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
				$content = curl_exec($ch);
				curl_close($ch);
				return $content;			
			}else{
				return file_get_contents($url);
			}
		}

		public static function safe_filename($filename) {
			return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
		}
	}