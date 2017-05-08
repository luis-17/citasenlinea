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

	public function m_consulta_cita_venta($idprogcita){
		$this->db->select('cv.idventa, cv.orden_venta, cv.fecha_venta'); 
		$this->db->select('cd.iddetalle, cd.paciente_atendido_det, cd.fecha_atencion_det'); 
		$this->db->select('ppc.idprogcita, ppc.estado_cita'); 
		$this->db->from("pa_prog_cita ppc");
		$this->db->join('ce_detalle cd','cd.idprogcita  = ppc.idprogcita');		
		$this->db->join('ce_venta cv','cd.idventa  = cv.idventa');		
		$this->db->where("ppc.idprogcita", intval($idprogcita)); //cita
		$this->db->where("ppc.idprogcita <> ", 0); //estado cita (no cancelada)
		return $this->db->get()->row_array();
	}
}