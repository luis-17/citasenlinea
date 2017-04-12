<?php
class Model_especialidad extends CI_Model {
	public function __construct()	{
		parent::__construct();
	}

	public function m_cargar_especialidades_prog_asistencial($datos){
		$this->db->select('esp.idespecialidad, (esp.nombre) AS especialidad');
		$this->db->from('especialidad esp');
		$this->db->join('pa_sede_especialidad seesp','esp.idespecialidad = seesp.idespecialidad AND seesp.idsede = '.$datos['idsede'], 'left');
		$this->db->where('esp.estado', 1); // especialidad
		$this->db->where('seesp.tiene_prog_cita', 1); // especialidad
		$this->db->order_by('esp.nombre ASC');
		
		return $this->db->get()->result_array();
	}



}