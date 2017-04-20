<?php
class Model_prog_cita extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_registrar($data){
		return $this->db->insert('pa_prog_cita', $data);
	}
}