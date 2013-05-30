<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Main {
	
		public static function index() {
			View::assign('username', 'Graham');
			View::render('mission');
		}
		
		public function test() {
			echo __CLASS__;
		}
	}