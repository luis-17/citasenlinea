<?php
class Model_usuario extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_cargar_por_documento($datos){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select('uw.idusuarioweb, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->from('cliente c');
		$this->db->join('ce_usuario_web uw','uw.idcliente = c.idcliente AND uw.estado_uw <> 0', 'left');
		$this->db->where('c.estado_cli', 1); 
		$this->db->where('c.num_documento',$datos['num_documento']);
		return $this->db->get()->row_array();
	}	

	public function m_cargar_usuario($datos){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select('uw.idusuarioweb, uw.nombre_usuario, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->from('ce_usuario_web uw');
		$this->db->join('cliente c','uw.idcliente = c.idcliente AND c.estado_cli = 1', 'left');
		$this->db->where('uw.estado_uw', 1); 
		$this->db->where('uw.nombre_usuario',$datos['nombre_usuario']);
		return $this->db->get()->row_array();
	}

	public function m_verificar_email($datos){
		$this->db->select('uw.idusuarioweb, uw.estado_uw', FALSE);
		$this->db->from('ce_usuario_web uw');
		$this->db->where('uw.estado_uw <>', 0); 
		$this->db->where('UPPER(uw.nombre_usuario)',strtoupper($datos['email']));
		return $this->db->get()->row_array();
	}

	public function m_registrar_cliente($data){
		return $this->db->insert('cliente', $data);
	}	

	public function m_update_cliente($data, $id){
		$this->db->where('idcliente',$id);
		return $this->db->update('cliente', $data);
	}

	public function m_registrar_usuario($data){
		return $this->db->insert('ce_usuario_web', $data);
	}	

	public function m_cargar_este_usuario($datos){ 
		$this->db->select('uw.idusuarioweb, uw.estado_uw', FALSE);
		$this->db->from('ce_usuario_web uw');
		$this->db->where('uw.estado_uw', 1); 
		$this->db->where('idusuarioweb',$datos['idusuario']);
		return $this->db->get()->row_array();
	}

	public function m_verifica_password($datos){ 
		$this->db->select('*');
		$this->db->from('users u');
		$this->db->where('idusers',$datos['id']);
		$this->db->where('password', do_hash($datos['clave'] , 'md5'));
		$this->db->where('estado_usuario <>', '0');
		$this->db->limit(1);
		
		return $this->db->get()->row_array();
	}

	public function m_actualizar_password($datos){
		$data = array(
			'password' => do_hash($datos['claveNueva'],'md5'),
			'password_watch' => $datos['claveNueva'],
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusers',$datos['id']);
		return $this->db->update('users', $data);
	}


	public function m_confirma_password($datos){ 
		$this->db->select('1 as result');
		$this->db->from('users u');
		$this->db->where('idusers',$datos['id']);
		$this->db->where('password', do_hash($datos['clave'] , 'md5'));
		$this->db->where('estado_usuario <>', '0');
		$this->db->limit(1);
		$fData = $this->db->get()->row_array();
		return $fData['result'];
	}



}