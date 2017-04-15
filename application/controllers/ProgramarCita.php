<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProgramarCita extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'fechas_helper'));
		$this->load->model(array('model_programar_cita','model_sede'));
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
	}

	public function cargar_planning(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		/*$allInputs['desde'] = date('d-m-Y', strtotime('23-03-2017'));
		$allInputs['hasta'] = date('d-m-Y', strtotime('29-03-2017'));*/
		
		/*header*/
		$datos = array('anyo' => date("Y"));
		$feriados = $this->model_programar_cita->m_lista_feriados_cbo($datos); 
		$arrFeriados = array();
		foreach ($feriados as $row) {
			array_push($arrFeriados,  $row['fecha']); 
		}

		$arrFechas = get_rangofechas($allInputs['desde'],$allInputs['hasta'],TRUE);

		$arrHeader = array();
		foreach ($arrFechas as $fecha) {
			array_push($arrHeader, 
				array(
					'fecha' => $fecha,
					'dato' => date("d-m-Y", strtotime($fecha)),
					'class' =>  (date("w", strtotime($fecha)) == 0 || in_array($fecha, $arrFeriados)) ? 'fecha-header feriado ' : 'fecha-header ',
					'es_feriado' => (date("w", strtotime($fecha)) == 0 || in_array($fecha, $arrFeriados)) ? TRUE : FALSE
				)
			);
		}

		/*sidebar*/
		$sede = $this->model_sede->m_cargar_sede_por_id($allInputs['itemSede']['id']);
		$number = intval(explode(":",$sede['hora_final_atencion'])[0]);
		$hora_fin = str_pad($number-1,2,"0",STR_PAD_LEFT) . ':00:00';
		$horas = get_rangohoras($sede['hora_inicio_atencion'], $hora_fin);
		$arrHoras = array();
		foreach ($horas as $item => $hora) {
			array_push($arrHoras, 
				array(
					'hora' => $hora,
					'dato' => darFormatoHora($hora),
					'class' =>  'hora-sidebar ',
				)
			);	

			$segundos_horaInicial=strtotime($hora); 
			$segundos_minutoAnadir=30*60; 
			$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
			$number = intval(explode(":",$nuevaHora)[0]);
			array_push($arrHoras, 
				array(
					'hora' => $nuevaHora,
					'dato' => darFormatoHora($nuevaHora),
					'class' => 'hora-sidebar '	,
				)
			);		
		}

		/*body*/
		$arrListado = array();
		$arrGridTotal = array();
				
		$lista = $this->model_programar_cita->m_cargar_programaciones($allInputs);

		$countHoras = count($arrHoras);
		$countFechas = count($arrFechas);
		$countProg = count($lista);

		$ind = 0;
		$i = 0;
		$j = 0;


		while ($j < $countFechas) {
			$fecha = $arrHeader[$j]['fecha'];
			$i = 0;
			$arrGrid = array();	
			while ($i < $countHoras){
				if(empty($arrGrid[$arrHoras[$i]['hora']])){
					$arrGrid[$arrHoras[$i]['hora']]= array(
						'dato' => '',
						'ids' => '',
						'idsmedicos' => '',
						'class' => 'cell-vacia',
						'rowspan' => 1,
						'unset' => FALSE,
					);
				}							

				foreach ($lista as $prog_row) {
					$segundos_horaFin=strtotime($prog_row['hora_fin']);
					$number = intval(explode(":",$prog_row['hora_fin'])[1]);
					if($number == 0 || $number == 30){
						$segundos_minutosResta=30*60;
						$hora_fin_comparar=date("H:i:s",$segundos_horaFin-$segundos_minutosResta);
					}else{
						$hora_fin_comparar=date("H:i:s",$segundos_horaFin);
					}

					if($arrHoras[$i]['hora'] >= $prog_row['hora_inicio'] 
						&& $arrHoras[$i]['hora'] <= $hora_fin_comparar 
						&& $arrFechas[$j] == $prog_row['fecha_programada']){
						$encontro = true;
						$arrGrid[$arrHoras[$i]['hora']]['dato'] = $prog_row['especialidad'];
						$arrGrid[$arrHoras[$i]['hora']]['ids'] .=  $prog_row['idprogmedico'] .',';
						$arrGrid[$arrHoras[$i]['hora']]['idsmedicos'] .= $prog_row['idmedico'] . ',';
						$arrGrid[$arrHoras[$i]['hora']]['class'] = 'cell-programacion ';						
						$arrGrid[$arrHoras[$i]['hora']]['especialidad'] = $prog_row['especialidad'];						
						$arrGrid[$arrHoras[$i]['hora']]['idespecialidad'] = $prog_row['idespecialidad'];
						if(!empty($allInputs['itemMedico']['idmedico']) && $prog_row['idmedico'] == $allInputs['itemMedico']['idmedico']){
							$arrGrid[$arrHoras[$i]['hora']]['medico_favorito'] = true;
						}						
					}
				}

				$i++;	
			}

			$arrGridTotal[$fecha] = $arrGrid;
			$j++;
		}


		foreach ($arrGridTotal as $key => $grid) {
			$arrGridTotal[$key] = array_values($grid);
		}
		$arrGridTotal = array_values($arrGridTotal);

		$cellTotal = count($arrHoras);
		$cellColumn = count($arrFechas);

		foreach ($arrFechas as $i => $fecha) {
	    	$inicio = -1;
   			$fin = -1;
   			$anterior = '';
   			$ite = 0;
	    	foreach ($arrHoras as $row => $value) {  
	    		$actual =  empty($arrGridTotal[$i][$row]['ids']) ? '' : $arrGridTotal[$i][$row]['ids']; 

	    		if($ite == 0){
	    			$anterior = $actual;
	    			$ite++;
	    		}  	

	    		if($inicio == -1)
	    			$inicio = $row;

	    		if($actual != $anterior) {
	    			if($actual == ''){
	    				$fin = $row-1;
	    			}else if(!( 
	    				( strlen($actual) < strlen($anterior) && mb_strstr($anterior, $actual) ) || 
	    				( strlen($actual) > strlen($anterior) && mb_strstr($actual, $anterior)) 
	    			)){
	    				$fin = $row-1;
	    			}	    				    			
	    		}		    		

	    		if($inicio != -1 && $fin != -1){
	    			$rowspan =($fin - $inicio) + 1;
	    			$arrGridTotal[$i][$inicio]['rowspan'] = $rowspan;
					for ($fila=$inicio+1; $fila <= $fin; $fila++) { 						
						$arrGridTotal[$i][$fila]['unset'] = TRUE;
						if(strlen($arrGridTotal[$i][$inicio+1]['ids']) < strlen($arrGridTotal[$i][$fila]['ids'])){
							$arrGridTotal[$i][$inicio]['ids'] = $arrGridTotal[$i][$fila]['ids'];
						}
						if(!empty($arrGridTotal[$i][$fila]['medico_favorito'])){
							$arrGridTotal[$i][$inicio]['medico_favorito'] = true;
						}
					} 				
					$inicio = $row;
					$fin = -1;
	    		}else if($row == $cellTotal-1){
	    			$fin = $row;
    				$rowspan =($fin - $inicio) + 1;
					$arrGridTotal[$i][$inicio]['rowspan'] = $rowspan;
					for ($fila=$inicio+1; $fila <= $fin; $fila++) { 
    					$arrGridTotal[$i][$fila]['unset'] = TRUE;
    					if(strlen($arrGridTotal[$i][$inicio+1]['ids']) < strlen($arrGridTotal[$i][$fila]['ids'])){
							$arrGridTotal[$i][$inicio]['ids'] = $arrGridTotal[$i][$fila]['ids'];
						}
						if(!empty($arrGridTotal[$i][$fila]['medico_favorito'])){
							$arrGridTotal[$i][$inicio]['medico_favorito'] = true;
						}
    				}
    			}
				
				$anterior = empty($arrGridTotal[$i][$row]['ids']) ? '' : $arrGridTotal[$i][$row]['ids']; 

	    	}	    	 
	    }
		
		
		$arrData['datos'] = $lista;
		if(empty($lista)){
			$arrData['planning']['mostrar'] = FALSE;
			$arrData['flag'] = 0;
			$arrData['planning']['mostraralerta'] = TRUE;
			$arrData['message'] = 'No hay programaciones en la fecha seleccionada.';
		}else{
			$arrData['planning']['mostrar'] = TRUE;
		}
		$arrData['planning']['grid'] = $arrGridTotal;
		$arrData['planning']['horas'] = $arrHoras;
    	$arrData['planning']['fechas'] = $arrHeader;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_turnos(){
		$this->load->view('programar-cita/turnos_formView');
	}

	public function cargar_turnos_disponibles(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs['ids'] =  substr($allInputs['ids'], 0, -1); 
		//print_r($allInputs['ids']);
		$ids = explode (',',$allInputs['ids']);

		$lista = $this->model_programar_cita->m_cargar_cupos_disponibles($ids);
		$arrGroup = array();
		foreach ($lista as $key => $row) {
			$hora_formato = darFormatoHora($row['hora_inicio_det']);
			$row['hora_formato'] = $hora_formato;
			$row['checked'] = false;
			if($row['si_adicional']	== 1){
				$row['adicional'] = true;
			}else{
				$row['adicional'] = false;
			}		

			$fecha_programada = date('d-m-Y',strtotime($row['fecha_programada']));		
			$medico = $row['med_nombres'] . ' ' . $row['med_apellido_paterno'] . ' ' . $row['med_apellido_materno'];			
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']] = $row;
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']]['medico'] = $medico;
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']]['fecha_programada'] = $fecha_programada;

			$arrGroup[$row['idprogmedico']]['medico'] = ucwords(strtolower($medico));	
			if( !empty($allInputs['medico']['idmedico']) && $row['idmedico'] == $allInputs['medico']['idmedico']){
				$arrGroup[$row['idprogmedico']]['medico-favorito'] = TRUE;
			}						
		}

		$arrData['datos']= $arrGroup;
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function lista_medicos_autocomplete(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true); // var_dump($allInputs); exit(); 		
		$lista = $this->model_programar_cita->m_cargar_medicos_autocomplete($allInputs);
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado, 
				array(
					'idmedico' => $row['idmedico'],
					'medico' => $row['medico'],		
				)
			);
		}
    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function actualizar_lista_citas_session(){
	    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
	    $arrData['datos'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];	    
	    $arrData['datos']['listaCitas'] = $allInputs['listaCitas'];	    
	    $this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']);
	    $arrData['flag'] = 1;
	    //print_r($_SESSION['sess_cevs_'.substr(base_url(),-8,7) ]);
	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	      return;
  	}
}