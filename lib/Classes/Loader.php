<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Loader {

		private static $_helpers = [];
		private static $_helper_paths = [APPLIB, LIB];

		public static function helpers($helpers = []) {
			foreach (self::prep_filename($helpers, '_helper') as $helper) {
				if (isset(self::$_helpers[$helper])) {
					continue;
				}

				$ext_helper = $helper;
				$ext_loaded = false;
				foreach (self::$_helper_paths as $path) {
					if (file_exists($path.'Helpers'.DS.$ext_helper.'.php')) {
						include_once $path.'Helpers'.DS.$ext_helper.'.php';
						return;
					}
				}

				if (!isset(self::$_helpers[$helper])) {
					return false;
				}
			}
		}

		private static function prep_filename($file, $ext) {
			if (!is_array($file)) {
				return [strtolower(str_replace([$ext, '.php'], '', $file).$ext)];
			}else{
				foreach ($file as $key => $val) {
					$file[$key] = strtolower(str_replace([$ext, '.php'], '', $val).$ext);
				}
				return $file;
			}
		}
	}