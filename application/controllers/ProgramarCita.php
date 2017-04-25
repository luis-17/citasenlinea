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
		$allInputs['desde'] = date('d-m-Y', strtotime('23-03-2017'));
		$allInputs['hasta'] = date('d-m-Y', strtotime('29-03-2017'));
		
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
				$row['si_adicional'] = true;
				$row['adicional'] = true;
			}else{
				$row['si_adicional'] = false;
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
			$respuesta = json_encode($charge);			
		  	$arrData['datos']['cargo'] = get_object_vars($charge);		  	
		  	$arrData['datos']['cargo']['outcome']= get_object_vars($arrData['datos']['cargo']['outcome']);
		  	$arrData['flag'] = 1;
		  	//$arrData['datos']['cargo']['id'] = '15';
			
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
					$arrData['message'] = $arrData['datos']['cargo']['outcome']['user_message'];
					$arrData['flag'] = 1;
					//$arrData['message'] = 'test enviando correo';

					//envio de mails
			        $listaDestinatarios = array();
			        array_push($listaDestinatarios, $allInputs['usuario']['email']);			        
			        $setFromAleas = 'Villa Salud';
			        $subject = 'Citas Programadas';
			        $cuerpo = $this->genera_body_mail_citas($allInputs, $listaCitasGeneradas);
			        $result = enviar_mail($subject, $setFromAleas,$cuerpo,$listaDestinatarios);
			        $arrData['flagMail']  = $result['flag'];
					$arrData['msgMail']  = $result['msgMail'];
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

  	private function genera_body_mail_citas($allInputs, $listaCitasGeneradas){
  		$cuerpo = '<!DOCTYPE html>
					<html lang="es">
					<head>
					    <meta charset="utf-8">
					    <meta name="author" content="Villa Salud">								    
					</head>';
        $cuerpo .= '<body style="font-family: sans-serif;padding: 10px 40px;" > 
	                  <div style="text-align: center;">
	                    <img style="width: 160px;" alt="Hospital Villa Salud" src="'.base_url(). 'assets/img/dinamic/empresa/logo-original.png">
	                  </div> <br />';
        $cuerpo .= '  <div style="font-size:16px;">  
                        Estimado(a) usuario: '.$allInputs['usuario']['paciente'] .', <br /> <br /> ';
        $cuerpo .= '    Hemos han sido regitradas las siguientes citas en tu cuenta:';
        $cuerpo .=    '</div>';
  		$cuerpo .= '<div class="citas scroll-pane" style="width: 100%;">
  						<ul class="list-citas">';
		foreach ($listaCitasGeneradas as $key => $cita) {			
			$cuerpo .= 		'<li style="">
			                    <div class="cita" style="font-size: 13px;">
			                      <div style="height: 30px;" >
			                      	<div style="width:31px;float: left;">👪 </div>
			                      	Cita para: <span class="cita-familiar">'.$cita['busqueda']['itemFamiliar']['descripcion'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;">🏨 </div> 
			                      	Sede: <span class="cita-sede">'.$cita['busqueda']['itemSede']['descripcion'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;font-size: 20px;">⚕️ </div> 
			                      	Especialidad: <span class="cita-esp">'.$cita['busqueda']['itemEspecialidad']['descripcion'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;">👨‍ </div> 
			                      	Médico: <span class="cita-medico">'.$cita['seleccion']['medico'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;">🗓 </div> 
			                      	Fecha: <span class="cita-turno">'.$cita['seleccion']['fecha_programada'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;">🕑 </div>
			                      	Hora: <span class="cita-turno">'.$cita['seleccion']['hora_formato'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;"> 📍 </div>
			                      	Consultorio: <span class="cita-ambiente">'.$cita['seleccion']['numero_ambiente'].'</span>
			                      </div>
			                      <div style="height: 30px;">
			                      	<div style="width:31px;float: left;">💰 </div>
			                      	Precio S/.: <span class="cita-precio">'.$cita['producto']['precio_sede'].'</span>
			                      </div>
			                    </div>                            
			                </li>';
			if($key < count($listaCitasGeneradas)-1){
				$cuerpo .= '<div style="border-bottom: 1px dotted rgb(154, 153, 157); margin-bottom: 5px;width: 50%;"> </div>';
			}   
		} 

		$cuerpo .= '	</ul>			            
			        </div>';

		$cuerpo .= '<div style="border-top: 2px dotted rgb(97, 97, 97); margin-bottom: 5px;"> </div>';

		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #616161;">
					  <span style="text-align:left;width:60%;">TOTAL PRODUCTOS: S/. </span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['totales']['total_productos'].'</span>
					</div>';
		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #616161;">
					  <span style="text-align:left;width:60%;">USO SERVICIO WEB: S/.</span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['totales']['total_servicio'].'</span>
					</div>';
		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #212121;">
					  <span style="text-align:left;width:60%;">TOTAL A PAGAR: S/. </span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['totales']['total_pago'].'</span>
					</div>';

      	$cuerpo .= '</body>';
        $cuerpo .= '</html>';

      	return $cuerpo;
  	}

	public function verifica_estado_cita(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Sólo puedes reprogramar citas sin atención y máximo 1 día previo a la fecha programada.';
		$arrData['flag'] = 0;		

		$cita = $this->model_prog_cita->m_consulta_cita($allInputs['idprogcita']);

		$hoy = strtotime(date('Y-m-d'));
		$fecha_atencion = strtotime($cita['fecha_atencion_cita']);
		$arrData['hoy'] = $hoy;
		$arrData['hoyFormato'] = date('Y-m-d');
		$arrData['fecha_atencion'] = $fecha_atencion;
		$arrData['fecha_atencion_Formato'] = $cita['fecha_atencion_cita'];
		
		if($cita['estado_cita'] == 2){
			$arrData['message'] = 'Reprogramar';
			$arrData['flag'] = 1;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($arrData));
	}

	public function ver_popup_planning(){
		$this->load->view('programar-cita/planningReprogramar_formView');
	}

	public function cambiar_cita(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true); 
		$arrData['message'] = 'La cita no pudo ser reprogramada. Intente nuevamente';
    	$arrData['flag'] = 0;
    	
    	$cita = $this->model_prog_cita->m_consulta_cita($allInputs['oldCita']['idprogcita']);
    	if($cita['estado_cita'] != 2){
    		$arrData['message'] = 'Solo puede reprogramar citas en estado Pendiente.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}

    	/*
    	if($this->model_prog_cita->m_cita_tiene_atencion($allInputs['oldCita'])){
    		$arrData['message'] = 'No puede reprogramar citas con atención registrada.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	} */

    	$this->db->trans_start();
 		//cupo a disponible
		$data = array(
			'estado_cupo' => 2,
			'iddetalleprogmedico' => $allInputs['oldCita']['iddetalleprogmedico'],
			);	
		$resulDetalle = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);
		//si cancelo y no es un adicional debo actualizar encabezado programacion
		$resultOldCuposCanal = FALSE;
		$resultOldCuposProg = FALSE;
		if(!$allInputs['oldCita']['si_adicional']){
			//actualizo cantidad de cupos disponibles y ocupados 
			$resultOldCuposCanal = $this->model_prog_medico->m_revertir_cupos_canales($allInputs['oldCita']); 
			$resultOldCuposProg = $this->model_prog_medico->m_revertir_cupos_programacion($allInputs['oldCita']);						
		}else{
			$resultOldCuposCanal = TRUE;
			$resultOldCuposProg = TRUE;
		}
     	
		if($resulDetalle && $resultOldCuposCanal && $resultOldCuposProg){
			//cambio id de cupo en cita
			$resultCita = FALSE;
			$datos = array(
				'idprogcita' => $allInputs['oldCita']['idprogcita'],
				'iddetalleprogmedico' => $allInputs['seleccion']['iddetalleprogmedico'],
				'fecha_atencion_cita' => $allInputs['seleccion']['fecha_programada'] . ' ' . $allInputs['seleccion']['hora_inicio_det'],
				);
			$resultCita = $this->model_prog_cita->m_cambiar_datos_en_cita($datos); //cita con nuevo iddetalleprogmedico
			if($resultCita ){
				$resultCuposCanal = $this->model_prog_medico->m_cambiar_cupos_canales($allInputs['seleccion']); 
				$resultCuposProg = $this->model_prog_medico->m_cambiar_cupos_programacion($allInputs['seleccion']);	
				$data = array(
					'estado_cupo' => 1,
					'iddetalleprogmedico' => $allInputs['seleccion']['iddetalleprogmedico'],
					);	
				$resulDetalleNuevo = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);

				
				if($resulDetalle && $resultOldCuposCanal && $resultOldCuposProg && $resultCita && $resultCuposCanal && $resultCuposProg && $resulDetalleNuevo){
					$arrData['message'] = 'La cita ha sido reprogramada correctamente';
	    			$arrData['flag'] = 1;
					
					//envio de mails
			        $listaDestinatarios = array();
			        array_push($listaDestinatarios, $this->sessionCitasEnLinea['email']);			        
			        $setFromAleas = 'Villa Salud';
			        $subject = 'Reprogramación de Cita';
					$cuerpo = $this->genera_body_mail_reprogramacion($allInputs);
					$result = enviar_mail($subject, $setFromAleas,$cuerpo,$listaDestinatarios);

					$arrData['flagMail']  = $result[0]['flag'];
					$arrData['msgMail']  = $result[0]['msgMail'];
				}
			}
						
		}	    		
    	
		$this->db->trans_complete();   

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_confirmacion(){
		$this->load->view('programar-cita/confirmaReprogramacion_formView');
	}

	private function genera_body_mail_reprogramacion($allInputs){
  		$cuerpo = '<!DOCTYPE html>
					<html lang="es">
					<head>
					    <meta charset="utf-8">
					    <meta name="author" content="Villa Salud">								    
					</head>';
        $cuerpo .= '<body style="font-family: sans-serif;padding: 10px 40px;" > 
	                  <div style="text-align: center;">
	                    <img style="width: 160px;" alt="Hospital Villa Salud" src="'.base_url(). 'assets/img/dinamic/empresa/logo-original.png">
	                  </div> <br />';
        $cuerpo .= '  <div style="font-size:16px;">  
                        Estimado(a) usuario: '. $this->sessionCitasEnLinea['paciente'] .',';
        $cuerpo .= '    Has reprogramado una de tus citas. Nueva cita:';
        $cuerpo .=    '</div>';
  		
  		$cuerpo .=	'<div style="margin-top:15px;">
	                    <div class="cita" style="font-size: 13px;">
	                      <div style="height: 30px;" >
	                      	<div style="width:31px;float: left;">👪 </div>
	                      	Cita para: <span class="cita-familiar">'. strtoupper($allInputs['oldCita']['itemFamiliar']['paciente']).' ('.$allInputs['oldCita']['itemFamiliar']['parentesco'].')</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🏨 </div> 
	                      	Sede: <span class="cita-sede">'.$allInputs['oldCita']['itemSede']['sede'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;font-size: 20px;">⚕️ </div> 
	                      	Especialidad: <span class="cita-esp">'.$allInputs['oldCita']['itemEspecialidad']['especialidad'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">👨‍ </div> 
	                      	Médico: <span class="cita-medico">'.$allInputs['seleccion']['medico'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🗓 </div> 
	                      	Fecha: <span class="cita-turno">'.$allInputs['seleccion']['fecha_programada'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🕑 </div>
	                      	Hora: <span class="cita-turno">'.$allInputs['seleccion']['hora_formato'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;"> 📍 </div>
	                      	Consultorio: <span class="cita-ambiente">'.$allInputs['seleccion']['numero_ambiente'].'</span>
	                      </div>
	                    </div>                            
	                </div>';
      	$cuerpo .= '</body>';
        $cuerpo .= '</html>';

      	return $cuerpo;
  	}
}