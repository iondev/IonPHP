<?php if (!defined('ROOT')) die('No direct script access allowed');

	class CSRF {

		private static $token;
		private static $token_name = 'csrf_token';
		private static $contents;
		protected $timeout = 300;

		public static function generate_token() {
	        // Create or overwrite the csrf entry in the seesion
	        $_SESSION['csrf'] = array();
	        $_SESSION['csrf']['time'] = time();
	        $_SESSION['csrf']['salt'] = self::randomString(32);
	        $_SESSION['csrf']['sessid'] = session_id();
	        $_SESSION['csrf']['ip'] = $_SERVER['REMOTE_ADDR'];
	        // Generate the SHA1 hash
	        $hash = self::calculateHash();
	        // Generate and return the token
	        return base64_encode($hash);
	    }

	    protected static function calculateHash() {
	        return sha1(implode('', $_SESSION['csrf']));
	    }

	    private static function randomString($len = 10) {
	        // Characters that may look like other characters in different fonts
	        // have been omitted.
	        $rString = '';
	        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
	        $charsTotal  = strlen($chars);
	        for ($i = 0; $i < $len; $i++) {
	            $rInt = (integer) mt_rand(0, $charsTotal);
	            $rString .= substr($chars, $rInt, 1);
	        }
	        return $rString;
	    }

	    protected static function checkTimeout($timeout=NULL) {
	        if (!$timeout) {
	            $timeout = self::$timeout;
	        }
	        return ($_SERVER['REQUEST_TIME'] - $_SESSION['csrf']['time']) < $timeout;
	    }

	    public function generateHiddenField() {
	        // Shortcut method to generate the entire form
	        // element containing the CSRF protection token
	        $token = self::generate_token();
	        return "<input type=\"hidden\" name=\"csrf\" value=\"$token\" />";
	    }

	    public static function checkToken($timeout=NULL) {
	        // Default timeout is 300 seconds (5 minutes)

	        // First check if csrf information is present in the session
	        if (isset($_SESSION['csrf'])) {

	            // Check the timeliness of the request
	            if (!$this->checkTimeout($timeout)) {
	                return FALSE;
	            }

	            // Check if there is a session id
	            if (session_id()) {
	                // Check if response contains a usable csrf token
	                $isCsrfGet = isset($_GET['csrf']);
	                $isCsrfPost = isset($_POST['csrf']);
	                if (($this->acceptGet and $isCsrfGet) or $isCsrfPost) {
	                    // Decode the received token hash
	                    $tokenHash = base64_decode($_REQUEST['csrf']);
	                    // Generate a new hash from the data we have
	                    $generatedHash = $this->calculateHash();
	                    // Compare and return the result
	                    if ($tokenHash and $generatedHash) {
	                        return $tokenHash == $generatedHash;
	                    }
	                }
	            }
	        }

	        // In all other cases return FALSE
	        return FALSE;
	    }
	}