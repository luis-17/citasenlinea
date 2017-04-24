<?php
class Model_prog_cita extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_registrar($data){
		return $this->db->insert('pa_prog_cita', $data);
	}

	public function m_consulta_cita($idprogcita){
		$this->db->select('*'); 
		$this->db->from("pa_prog_cita ppc");		
		$this->db->where("ppc.idprogcita", intval($idprogcita)); //cita
		return $this->db->get()->row_array();
	}

	public function m_cambiar_datos_en_cita($datos){
		$data = array( 
			'iddetalleprogmedico'=> $datos['iddetalleprogmedico'], 
			'fecha_atencion_cita'=> $datos['fecha_atencion_cita'], 
		);
		$this->db->where("idprogcita", $datos['idprogcita']);
		return $this->db->update('pa_prog_cita', $data ); 
	}
}