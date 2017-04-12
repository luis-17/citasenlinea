<?php
class Model_sede extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_sedes_cbo($datos = FALSE){ 
		$this->db->select('idsede, descripcion, estado_se, hora_inicio_atencion, hora_final_atencion');
		$this->db->from('sede');
		$this->db->where('estado_se', 1); // activo
		if( $datos ){
			if($datos['nameColumn'] == 'tiene_prog_cita')
				$this->db->where($datos['nameColumn'] .' = ' . $datos['search']);
			else
				$this->db->like('LOWER('.$datos['nameColumn'].')', strtolower($datos['search']));
		}else{
			$this->db->limit(100);
		}
		$this->db->order_by('idsede','ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_sede_por_id($id){
		$this->db->select('idsede, descripcion, direccion_se, hora_inicio_atencion, hora_final_atencion, intervalo_sede');
		$this->db->from('sede');
		$this->db->where('estado_se',1);
		$this->db->where('idsede',$id);
		return $this->db->get()->row_array();
	}	

}