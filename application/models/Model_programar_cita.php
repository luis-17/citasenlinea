<?php
class Model_programar_cita extends CI_Model {
	public function __construct()	{
		parent::__construct();
	}

	public function m_cargar_programaciones($datos){
		$this->db->select('prm.idprogmedico, prm.fecha_programada, prm.hora_inicio, prm.hora_fin');
		$this->db->select('am.idambiente, am.numero_ambiente, esp.idespecialidad, esp.nombre AS especialidad'); 
		$this->db->select('prm.idmedico'); 
		$this->db->from('pa_prog_medico prm');
		$this->db->join('pa_ambiente am','prm.idambiente = am.idambiente');		
		$this->db->join('especialidad esp','prm.idespecialidad = esp.idespecialidad');		
		$this->db->where('DATE(prm.fecha_programada) BETWEEN '. $this->db->escape($datos['desde']). ' AND '. $this->db->escape($datos['hasta'])); 

		if(!empty($datos['itemEspecialidad']) && !empty($datos['itemEspecialidad']['id'])){
			$this->db->where('esp.idespecialidad',$datos['itemEspecialidad']['id']);
		}

		/*if(!empty($datos['itemMedico']) && $datos['itemMedico']['idmedico'] != null){
			$this->db->where('prm.idmedico',$datos['itemMedico']['idmedico']);
		}*/

		$this->db->where('estado_amb', 1);		 
		$this->db->where('estado_prm', 1);		 
		$this->db->where('prm.idsede', $datos['itemSede']['id']); 
		$this->db->where('(	SELECT count(*) 
						   	FROM pa_detalle_prog_medico 
							WHERE idprogmedico = prm.idprogmedico 
							AND estado_cupo = 2
							AND idcanal = 3) > 0');

		//$this->db->group_by('prm.fecha_programada, am.numero_ambiente, am.idambiente, esp.idespecialidad'); 
		$this->db->order_by('prm.fecha_programada ASC, prm.hora_inicio ASC'); 
		return $this->db->get()->result_array(); 
	}

	public function m_cargar_cupos_disponibles($idsprogmedicos){
		$this->db->select('dpm.iddetalleprogmedico, dpm.idcanal, dpm.hora_inicio_det, dpm.hora_fin_det');
		$this->db->select('dpm.si_adicional, dpm.numero_cupo, prm.idprogmedico, prm.idmedico');
		$this->db->select('med.med_nombres, med.med_apellido_paterno, med.med_apellido_materno, med.colegiatura_profesional');		
		$this->db->select('prm.fecha_programada');		

		$this->db->from('pa_detalle_prog_medico dpm');
		$this->db->join('pa_prog_medico prm','dpm.idprogmedico = prm.idprogmedico');
		$this->db->join('medico med', 'med.idmedico = prm.idmedico');		 
		$this->db->where('dpm.estado_cupo', 2);		 
		$this->db->where('dpm.idcanal', 3); //canal web		 
		$this->db->where_in('dpm.idprogmedico', $idsprogmedicos);		 
		
		$this->db->order_by('prm.idprogmedico ASC, dpm.si_adicional DESC, dpm.hora_inicio_det ASC');
		return $this->db->get()->result_array(); 
	}


	public function m_cargar_medicos_autocomplete($datos){
		$this->db->distinct();
		$this->db->select("m.idmedico");
		$this->db->select("(med_nombres || ' ' || med_apellido_paterno || ' ' || med_apellido_materno ) AS medico", FALSE);		
		$this->db->from('medico m'); //medico
		$this->db->join('empresa_medico emme','m.idmedico = emme.idmedico'); //empresa_medico
		$this->db->join('empresa_especialidad emes','emme.idempresaespecialidad = emes.idempresaespecialidad');		
		$this->db->join('especialidad esp','emes.idespecialidad = esp.idespecialidad', "emme.idsede = " .(int)$datos['itemSede']['idsede']);			
		$this->db->where('esp.idespecialidad',(int)$datos['itemEspecialidad']['id']);
	
		$this->db->ilike("med_nombres || ' ' || med_apellido_paterno || ' ' || med_apellido_materno", strtoupper($datos['search']));		
				
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}


	public function m_lista_feriados_cbo($paramDatos){ 
		$anioSig = intval($paramDatos['anyo'] + 1);
		$this->db->select('idferiado, fecha, estado_fe, descripcion');
		$this->db->from('rh_feriado');
		$this->db->where('estado_fe', 1); // activo
		$this->db->where("date_part('year', fecha) = ".$paramDatos['anyo'] . " OR  date_part('year', fecha) = " . $anioSig);
		$this->db->order_by('fecha', 'ASC');
		
		return $this->db->get()->result_array();
	}


}