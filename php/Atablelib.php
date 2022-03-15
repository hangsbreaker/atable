<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Atablelib{
	function __construct(){
		$CI = & get_instance();
		include APPPATH . 'third_party/Atable.php';
	}

	function init(){
		return atable_init();
	}

	function setup(){
		return new atable();
	}
}
