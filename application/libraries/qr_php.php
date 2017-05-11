<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Qr_php {
		public function Qr_php(){
			require_once (dirname(__FILE__)."/phpqrcode/qrlib.php");
			//include_once dirname(__FILE__)."/phpqrcode/qrlib.php";
		}
	}

?>