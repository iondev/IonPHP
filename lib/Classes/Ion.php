<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Ion {
		
		public static $version = '1.0';
		public static $bootstrapping = FALSE;
		protected static $_cacheChange = FALSE;
		protected static $_classMap, $_packages, $_map = array();
		public static $legacy = array(
				'models' => 'Model',
				'behaviors' => 'Model/Behavior',
				'datasources' => 'Model/Datasource',
				'controllers' => 'Controller',
				'components' => 'Controller/Component',
				'views' => 'View',
				'helpers' => 'View/Helper',
				'shells' => 'Console/Command',
				'libs' => 'Lib',
				'vendors' => 'Vendor',
				'plugins' => 'Plugin',
				'locales' => 'Locale'
		);
		
		/*
		 * Method for autoloading class files
		 */
		public static function load($className, $location) {
			self::$_classMap[$className] = $location;
		}
		
		protected static function _mapped($name, $plugin = NULL) {
			$key = $name;
			if ($plugin) {
				$key = 'plugin'.$name;
			}
			return isset(self::$_map[$key]) ? self::$_map[$key] : FALSE;
		}
		
		public static function path($type, $plugin = null) {
			if (!empty(self::$legacy[$type])) {
				$type = self::$legacy[$type];
			}
			if (!empty($plugin)) {
				$path = array();
				$pluginPath = self::pluginPath($plugin);
				$packageFormat = self::_packageFormat();
				if (!empty($packageFormat[$type])) {
					foreach ($packageFormat[$type] as $f) {
						$path[] = sprintf($f, $pluginPath);
					}
				}
				return $path;
			}
			if (!isset(self::$_packages[$type])) {
				return array();
			}
			return self::$_packages[$type];
		}
		
		protected static function _map($file, $name, $plugin = null) {
			$key = $name;
			if ($plugin) {
				$key = 'plugin.' . $name;
			}
			if ($plugin && empty(self::$_map[$name])) {
				self::$_map[$key] = $file;
			}
			if (!$plugin && empty(self::$_map['plugin.' . $name])) {
				self::$_map[$key] = $file;
			}
			if (!self::$bootstrapping) {
				self::$_cacheChange = true;
			}
		}
		
		public static function _load($className) {
			if (!isset(self::$_classMap[$className])) {
				return FALSE;
			}
			$parts = explode('.', self::$_classMap[$className], 2);
			list($plugin, $package) = count($parts) > 1 ? $parts : array(null, current($parts));
			if ($file = self::_mapped($className, $plugin)) {
				return include $file;
			}
			$paths = self::path($package, $plugin);
			if (empty($plugin)) {
				$APPLIB = empty(self::$_packages['Lib']) ? APPLIB : current(self::$_packages['Lib']);
				$paths[] = $APPLIB . $package . DS;
				$paths[] = APPPATH . $package . DS;
				$paths[] = LIB . $package . DS;
			} else {
				$pluginPath = self::pluginPath($plugin);
				$paths[] = $pluginPath . 'Lib' . DS . $package . DS;
				$paths[] = $pluginPath . $package . DS;
			}
			$normalizedClassName = str_replace('\\', DS, $className);
			foreach ($paths as $path) {
				$file = $path . $normalizedClassName . '.php';
				if (file_exists($file)) {
					self::_map($file, $className, $plugin);
					return include $file;
				}
			}
			return false;
		}
		
		/*
		 * VERSION: calls back the current version of the framework
		 */
		public static function version() {
			return self::$version;
		}
		
		public static function import($name) {
			//Check if the plugin dir is real
			if (!is_dir(PLUGIN.$name)) {
				exit("{$name} does not exist");
			}
			
			//Check if the base file is there
			if (!file_exists(PLUGIN.$name.DS.'Base.php')) {
				exit("Missing Base.php in {$name}");
			}
			
			require_once PLUGIN.$name.DS.'Base.php';
			$obj = new $name;
			return $obj;
		}
	}