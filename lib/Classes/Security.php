<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Security {

		protected static $magic_quotes_gpc = false;

		public static function defend() {
			//Check for magic quotes
			if (get_magic_quotes_runtime()) {
				set_magic_quotes_runtime(0);
			}

			//This is bad and deprected
			if (get_magic_quotes_gpc()) {
				self::$magic_quotes_gpc = true;
			}

			//Check for register globals and prevent secruity issues from arising
			if (ini_get('register_globals')) {
				if (isset($_REQUEST['GLOBALS'])) {
					//Attack detected
					exit('Illegal attack on global variable');
				}

				//Clear $_REQUEST
				$_REQUEST = [];

				//These should be removed
				$preserve = [
					'GLOBALS',
					'_REQUEST',
					'_GET',
					'_POST',
					'_FILES',
					'_COOKIE',
					'_SERVER',
					'_ENV',
					'_SESSION'
				];

				//Saitize global data
				if (is_array($_POST)) {
					foreach ($_POST as $key => $value) {
						$_POST[self::clean_input_keys($key)] = self::clean_input_data($value);
					}
				}else{
					$_POST = [];
				}

				if (is_array($_GET)) {
					foreach ($_GET as $key => $value) {
						$_GET[self::clean_input_keys($key)] = self::clean_input_data($value);
					}
				}else{
					$_GET = [];
				}

				if (is_array($_COOKIE)) {
					foreach ($_COOKIE as $key => $value) {
						$_COOKIE[self::clean_input_keys($key)] = self::clean_input_data($value);
					}
				}else{
					$_COOKIE = [];
				}

				$_REQUEST = array_merge($_GET, $_POST);
			}
		}

		public static function xss_clean($data) {
			if (empty($data)) return $data;

	        if(is_array($data)) {
	            foreach($data as $key => $value) {
	                $data[$key] = self::xss_clean($data);
	            }
	            
	            return $data;
	        }

	        $data = str_replace(array('&','<','>'), array('&','<','>'), $data);
	        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	        // Remove any attribute starting with "on" or xmlns
	        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	        // Remove javascript: and vbscript: protocols
	        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	        // Remove namespaced elements (we do not need them)
	        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	        do {
	        	$old_data = $data;
            	$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	        }

	        while ($old_data !== $data);

	        return $data;
		}

		protected static function clean_input_keys($data) {
	        $chars = PCRE_UNICODE_PROPERTIES ? '\pL' : 'a-zA-Z';
	        
	        if ( ! preg_match('#^[' . $chars . '0-9:_.-]++$#uD', $data))
	        {
	            exit('Illegal key characters in global data');
	        }
	        
	        return $data;			
		}

		protected static function clean_input_data($data) {
	        if(is_array($data)) {
	            $new_array = array();
	            foreach($data as $key => $value) {
	                $new_array[self::clean_input_keys($key)] = self::clean_input_data($value);
	            }
	            
	            return $new_array;
	        }
	        
	        if(self::$magic_quotes_gpc === TRUE) {
	            // Get rid of those pesky magic quotes!
	            $data = stripslashes($data);
	        }
	        
	        $data = self::xss_clean($data);
	        
	        return $data;
    }
}