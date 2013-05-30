<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Main {
	
		public static function index($home = null, $test = 1) {
			//Ion::import('reCAPTCHA');
			View::assign('username', 'Graham');
			View::render('test');
			
			echo $home." is ".$test;
		}
		
		public function test() {
			echo __CLASS__;
		}
	}