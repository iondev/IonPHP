<?php if (!defined('ROOT')) die('No direct script access allowed');

	if (!function_exists('env')) {
		function env($key) {
			if ($key === 'HTTPS') {
				if (isset($_SERVER['HTTPS'])) {
					return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
				}
				return (strpos(env('SCRIPT_URI'), 'https://') === 0);
			}
			if ($key === 'SCRIPT_NAME') {
				if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
					$key = 'SCRIPT_URL';
				}
			}
			$val = null;
			if (isset($_SERVER[$key])) {
				$val = $_SERVER[$key];
			} elseif (isset($_ENV[$key])) {
				$val = $_ENV[$key];
			} elseif (getenv($key) !== false) {
				$val = getenv($key);
			}
			if ($key === 'REMOTE_ADDR' && $val === env('SERVER_ADDR')) {
				$addr = env('HTTP_PC_REMOTE_ADDR');
				if ($addr !== null) {
					$val = $addr;
				}
			}
			if ($val !== null) {
				return $val;
			}
			switch ($key) {
				case 'SCRIPT_FILENAME':
					if (defined('SERVER_IIS') && SERVER_IIS === true) {
						return str_replace('\\\\', '\\', env('PATH_TRANSLATED'));
					}
					break;
				case 'DOCUMENT_ROOT':
					$name = env('SCRIPT_NAME');
					$filename = env('SCRIPT_FILENAME');
					$offset = 0;
					if (!strpos($name, '.php')) {
						$offset = 4;
					}
					return substr($filename, 0, -(strlen($name) + $offset));
				case 'PHP_SELF':
					return str_replace(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
				case 'CGI_MODE':
					return (PHP_SAPI === 'cgi');
				case 'HTTP_BASE':
					$host = env('HTTP_HOST');
					$parts = explode('.', $host);
					$count = count($parts);
			
					if ($count === 1) {
						return '.' . $host;
					} elseif ($count === 2) {
						return '.' . $host;
					} elseif ($count === 3) {
						$gTLD = array(
								'aero',
								'asia',
								'biz',
								'cat',
								'com',
								'coop',
								'edu',
								'gov',
								'info',
								'int',
								'jobs',
								'mil',
								'mobi',
								'museum',
								'name',
								'net',
								'org',
								'pro',
								'tel',
								'travel',
								'xxx'
						);
						if (in_array($parts[1], $gTLD)) {
							return '.' . $host;
						}
					}
					array_shift($parts);
					return '.'.implode('.', $parts);
			}
			return null;
		}
	}
	
	if (!function_exists('show_error')) {
		function show_error($message, $code = 500) {
			@header("{$code} - {$message}", true, $code);
			die("{$code} - {$message}");
		}
	}

    if (!function_exists('app_version')) {
        function app_version() {
            return floatval(1.0);
        }
    }