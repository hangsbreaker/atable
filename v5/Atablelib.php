<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Atablelib{
	function atablelib(){
		$CI = & get_instance();
		log_message('Debug', 'Atable class is loaded.');
	}

	function init(){
		include APPPATH.'third_party/atable/atable.php';
		atable_init();
	}

	function setup(){
		return new atable();
	}
}
