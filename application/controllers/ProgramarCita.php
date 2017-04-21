<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProgramarCita extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'fechas_helper', 'otros_helper'));
		$this->load->model(array('model_programar_cita',
								 'model_sede', 
								 'model_especialidad',
								 'model_prog_medico',
								 'model_prog_cita'
								 ));

		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
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
						$arrGrid[$arrHoras[$i]['hora']]['fecha_programada'] = date('d-m-Y',strtotime($prog_row['fecha_programada']));
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

	public function ver_popup_aviso(){
		$this->load->view('mensajes/alerta');
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
			$arrGroup[$row['idprogmedico']]['ambiente']['numero_ambiente'] = $row['numero_ambiente'];
			$arrGroup[$row['idprogmedico']]['ambiente']['idambiente'] = $row['idambiente'];

			$arrGroup[$row['idprogmedico']]['medico'] = ucwords(strtolower($medico));	
			if( !empty($allInputs['medico']['idmedico']) && $row['idmedico'] == $allInputs['medico']['idmedico']){
				$arrGroup[$row['idprogmedico']]['medico_favorito'] = TRUE;
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
	    
	    $arrListado = array();
	    $total_productos = 0;
	    foreach ($allInputs['listaCitas'] as $key => $cita) {
	    	if($cita['busqueda']['itemSede']['idsede'] == 1){
	    		$cita['busqueda']['itemSede']['idempresaadmin'] = 14; //Sede Villa - Empresa GM
	    	}

	    	if($cita['busqueda']['itemSede']['idsede'] == 3){
	    		$cita['busqueda']['itemSede']['idempresaadmin'] = 15; //Sede Lurin - Empresa Med. I. 
	    	}

	    	$datosCita = array(
	    		'idespecialidad' => $cita['busqueda']['itemEspecialidad']['id'],
	    		'especialidad' => $cita['busqueda']['itemEspecialidad']['descripcion'],
	    		'idsede' => $cita['busqueda']['itemSede']['idsede'],
	    		'idempresaadmin' => $cita['busqueda']['itemSede']['idempresaadmin'],
	    	);
	    	$row_precio = $this->model_especialidad->m_cargar_precio_cita($datosCita);
	    	//print_r($row_precio);
	    	$cita['producto'] = $row_precio[0];
	    	$total_productos += (float)$cita['producto']['precio_sede'];
	    	array_push($arrListado, $cita);	    	
	    }

	    $config = getConfig('culqi');
	    $porcentaje = (float)$config['PORCENTAJE']; //3.99;
	    $comision = (float)$config['COMISION']; //0.15;
	    $tasa_cambio = (float)$config['TASA_CAMBIO']; // 3.3; 
	    $total_servicio =  ($total_productos * $porcentaje / 100) + ($comision * $tasa_cambio);
	    $total_pago = $total_productos + $total_servicio;

	    $arrData['datos']['listaCitas'] = $arrListado;
	    $arrData['datos']['totales']['total_productos'] = number_format(round($total_productos,2),2);
	    $arrData['datos']['totales']['total_servicio'] = number_format(round($total_servicio,2),2);
	    $arrData['datos']['totales']['total_pago'] = number_format(round($total_pago,2),2);
	    
	    $str_total = (string)$arrData['datos']['totales']['total_pago'];
	    $arrData['datos']['totales']['total_pago_culqi'] = str_replace('.', '',$str_total);

	    $this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']);
	    $arrData['flag'] = 1;
	    //print_r($_SESSION['sess_cevs_'.substr(base_url(),-8,7) ]);
	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	    return;
  	}

  	public function validar_citas(){

  	}

  	public function generar_venta(){
  		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$config = getConfig('culqi');
		$arrData['flag'] = 0;
		$arrData['message'] = 'Ha ocurrido un error procesando tu pago. Intenta nuevamente!.';
	
		$this->load->library(array('culqi_php'));	
		$respuesta = array();

		try{			
			$culqi = new Culqi\Culqi(array('api_key' => $config['CULQI_PRIVATE_KEY']));

			$charge = $culqi->Charges->create(
				array(
					"amount" => $allInputs['usuario']['totales']['total_pago_culqi'],
					"currency_code" => "PEN",
					"email" => $allInputs['token']['email'],
					"description" => "Pago de Citas en linea - Villa Salud",
					"installments" => 0,
					"source_id" => $allInputs['token']['id'] 
				)
			);
			// Respuesta
			//$respuesta = json_encode($charge);
			$arrData['flag'] = 1;
		  	$arrData['datos']['cargo'] = get_object_vars($charge);		  	
		  	$arrData['datos']['cargo']['outcome']= get_object_vars($arrData['datos']['cargo']['outcome']);
			
		  	if($arrData['flag'] == 1 && !empty($arrData['datos']['cargo']['id'])){
				$this->db->trans_start();
				$arrData['flag'] = 0;
				$listaCitas = $allInputs['usuario']['listaCitas'];
				$listaCitasGeneradas = $allInputs['usuario']['listaCitas'];
				$error = FALSE;
				foreach ($listaCitas as $key => $cita) {
					$result = FALSE;
					$resultDetalle = FALSE;
					$resultCanales = FALSE;
					$resultProg = FALSE;

					if($cita['busqueda']['itemFamiliar']['idusuariowebpariente'] == 0){
						$cliente = $allInputs['usuario']['idcliente'];
					}else{
						$cliente = $cita['busqueda']['itemFamiliar']['idclientepariente'];
					} 

					//registro de cita
					$data = array(
						'iddetalleprogmedico' => $cita['seleccion']['iddetalleprogmedico'],
						'fecha_reg_reserva' => date('Y-m-d H:i:s'),
						'fecha_reg_cita' => date('Y-m-d H:i:s'),
						'fecha_atencion_cita' => $cita['seleccion']['fecha_programada']. " " . $cita['seleccion']['hora_inicio_det'],
						'idcliente' => $cliente,
						'idempresacliente' =>  NULL,
						'estado_cita' => 2,
						'idproductomaster' => $cita['producto']['idproductomaster'],
						'idsedeempresaadmin' => $cita['producto']['idsedeempresaadmin'],
						);
					$result = $this->model_prog_cita->m_registrar($data);

					if($result){
						$idprogcita = GetLastId('idprogcita','pa_prog_cita');
						$listaCitasGeneradas[$key]['idprogcita'] =  $idprogcita;
						$listaCitasGeneradas[$key]['idventa'] =  '';
						$listaCitasGeneradas[$key]['idculqitracking'] =  $arrData['datos']['cargo']['id'];

						//actualizacipn de programacion
						$data = array(
							'iddetalleprogmedico' => $cita['seleccion']['iddetalleprogmedico'],
							'estado_cupo' => 1
							);
						$resultDetalle = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);

						$data = array(
							'idprogmedico' => $cita['seleccion']['idprogmedico'],
							'idcanal' => $cita['seleccion']['idcanal']
							);
						$resultCanales = $this->model_prog_medico->m_cambiar_cupos_canales($data);

						$data = array(
							'idprogmedico' => $cita['seleccion']['idprogmedico'],
							);
						$resultProg = $this->model_prog_medico->m_cambiar_cupos_programacion($data);						
						//fin actualizacion de programacion -- 

						//registro de relacion cita - usuario web
						if($resultDetalle && $resultCanales && $resultProg){
							$data=array(
								'idusuarioweb' => $allInputs['usuario']['idusuario'],
								'idprogcita' => $idprogcita,
								'idcliente' => $cliente,
								'idparentesco' => $cita['busqueda']['itemFamiliar']['idusuariowebpariente']
								);
							if(!$this->model_programar_cita->m_registrar_usuarioweb_cita($data)){
								$error = TRUE;
							}
						}else{
							$error = TRUE;
						}
					}else{
						$error = TRUE;
					}					
				}

				if(!$error){
					$arrData['datos']['session'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];
					$arrData['datos']['session']['listaCitas'] = array();
					$arrData['datos']['session']['listaCitasGeneradas'] = $listaCitasGeneradas;
					$this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']['session']);

					$arrData['flag'] = 1;
					$arrData['message'] = $arrData['datos']['cargo']['outcome']['user_message'];
				}
				$this->db->trans_complete();
			}
		}catch (Exception $e) {
		  	$arrData['datos']['error'] = get_object_vars(json_decode($e->getMessage()));
		  	$arrData['message'] = $arrData['datos']['error']['user_message'];
		}

	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	      return;
  	}
}