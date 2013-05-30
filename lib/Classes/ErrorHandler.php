<?php if (!defined('ROOT')) die('No direct script access allowed');

	class ErrorHandler {
		
		private $errors = array(
	        1       => 'Error',
	        2       => 'Warning',
	        4       => 'Parse error',
	        8       => 'Notice',
	        16      => 'Core Error',
	        32      => 'Core Warning',
	        256     => 'User Error',
	        512     => 'User Warning',
	        1024    => 'User Notice',
	        2048    => 'Strict',
	        4096    => 'Recoverable Error',
	        8192    => 'Deprecated',
	        16384   => 'User Deprecated',
	        32767   => 'All'
	    );
		
		public static function setErrorHandling() {
			set_error_handler('errorHandler');
			set_exception_handler('exceptionHandler');
		}
		
		public function exceptionHandler($exception) {
			$message = $exception->getMessage().' [Code: '.$exception->getCode().']';
			echo $message;
		}
		
		public function errorHandler($errno, $errstr, $errfile, $errline) {
			$errString = (array_key_exists($errno, $this->errorConstants))
			? $this->errorConstants[$errno] : $errno;
			
			echo ''.$errString.': '.$errstr;
			error_log($errString.' ['.$errno.']: '.$errstr.' in '.$errfile.' on line '.$errline);
		}
	}