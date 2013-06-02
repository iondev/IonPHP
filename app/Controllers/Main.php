<?php if (!defined('ROOT')) die('No direct script access allowed');

	class Main {
	
		public static function index() {
            $model = View::model('Blog');
            View::assign('recall', $model::recall("Hello Model"));
			View::render('mission');
		}
		
		public function test() {
			echo __CLASS__;
		}
	}