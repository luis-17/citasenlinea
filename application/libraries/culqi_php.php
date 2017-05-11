<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culqi_php {
	public function Culqi_php(){
		require_once('requests.php');
		Requests::register_autoloader();
		require_once('culqi.php');
	}
}

?>