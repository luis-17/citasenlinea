<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','otros_helper'));
		$this->load->model(array('model_acceso','model_usuario','model_historial_citas','model_control_evento_web'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
	}

	public function index(){
		//$this->load->library('encrypt');
		$allInputs = json_decode(trim(file_get_contents('php://input')),true);
		if($allInputs){ 
			$loggedUser = $this->model_acceso->m_logging_user($allInputs);
			if( isset($loggedUser['logged']) && $loggedUser['logged'] > 0 ){
				if($loggedUser['estado_uw'] == 1){
					$arrData['flag'] = 1;
					$arrPerfilUsuario = array();
					$arrPerfilUsuario['nombre_usuario'] = $loggedUser['nombre_usuario'];
					$arrPerfilUsuario['idusuario'] = $loggedUser['idusuarioweb'];

					$perfil = $this->model_usuario->m_cargar_usuario($arrPerfilUsuario);
					$tipo_sangre = array('', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-' ,'AB+', 'AB-');

					$arrPerfilUsuario['idcliente'] = $perfil['idcliente'];
					$arrPerfilUsuario['num_documento'] = $perfil['num_documento'];
					$arrPerfilUsuario['nombres'] = $perfil['nombres'];
					$arrPerfilUsuario['apellido_paterno'] = $perfil['apellido_paterno'];
					$arrPerfilUsuario['apellido_materno'] = $perfil['apellido_materno'];
					$arrPerfilUsuario['sexo'] = $perfil['sexo'];
					$arrPerfilUsuario['edad'] = $perfil['edad'];
					$arrPerfilUsuario['telefono'] = $perfil['telefono'];
					$arrPerfilUsuario['celular'] = $perfil['celular'];
					$arrPerfilUsuario['fecha_nacimiento'] = date('d-m-Y',strtotime($perfil['fecha_nacimiento']));
					$arrPerfilUsuario['email'] = $perfil['email'];
					$arrPerfilUsuario['nombre_imagen'] = empty($perfil['nombre_imagen']) ? 'noimage.png' : $perfil['nombre_imagen'];
					$arrPerfilUsuario['peso'] = $perfil['peso'];
				    $arrPerfilUsuario['estatura'] = $perfil['estatura'];
				    $arrPerfilUsuario['tipo_sangre']['id'] = empty($perfil['tipo_sangre']) ? null  :$perfil['tipo_sangre'];
				    $arrPerfilUsuario['tipo_sangre']['descripcion'] = empty($perfil['tipo_sangre']) ? null : $tipo_sangre[$perfil['tipo_sangre']] ;
					$arrPerfilUsuario['compra'] =array();
					$arrPerfilUsuario['compra']['listaCitas'] =array();

					$paciente = ucwords(strtolower( $perfil['nombres'] . ' ' . 
											$perfil['apellido_paterno'] . ' ' . 
											$perfil['apellido_materno']));
					
					$arrPerfilUsuario['paciente'] = $paciente;

					$arrPerfilUsuario['imc'] = array();
				    if(!empty($perfil['peso']) && !empty($perfil['estatura'])){
				      $imc = round($perfil['peso'] / ($perfil['estatura'] * $perfil['estatura']),2);
				      $arrPerfilUsuario['imc']['dato'] = (float)$imc;

				      if($imc < 18){
				        $tipoImc = 'Bajo peso';
				        $color = '#DEBD07';
				      }else if($imc >= 18 && $imc <=24.9){
				        $tipoImc = 'Normal';
				        $color = '#55DE40';
				      }else if($imc >= 25 && $imc <=26.9){
				        $tipoImc = 'Sobrepeso';
				        $color = '#DEBD07';
				      }else if($imc >= 27 && $imc <=29.9){
				        $tipoImc = 'Obesidad grado I';
				        $color = '#EDA94F';
				      }else if($imc >= 30 && $imc <=39.9){
				        $tipoImc = 'Obesidad grado II';
				        $color = '#E17317';
				      }else if($imc >= 40){
				        $tipoImc = 'Obesidad grado III';
				        $color = '#ce1d19';
				      }

				      $arrPerfilUsuario['imc']['tipo'] = $tipoImc;      
				      $arrPerfilUsuario['imc']['color'] = $color;     
				    }

				    if( isset($arrPerfilUsuario['idusuario']) ){
				    	$data = array(
				    		'idusuario' => $arrPerfilUsuario['idusuario'],
				    		'estado_cita' => 5
				    		);
				    	$citas_realizadas = $this->model_historial_citas->m_count_cant_citas($data);
				    	$arrPerfilUsuario['citas_realizadas'] = $citas_realizadas;

				    	$data = array(
				    		'idusuario' => $arrPerfilUsuario['idusuario'],
				    		'estado_cita' => 2
				    		);
				    	$citas_pendientes = $this->model_historial_citas->m_count_cant_citas($data);
				    	$arrPerfilUsuario['citas_pendientes'] = $citas_pendientes;
				    }
					
					// GUARDAMOS EN EL LOG DE LOGEO LA SESION INICIADA. 
					//$this->model_acceso->m_registrar_log_sesion($arrPerfilUsuario);
					// ACTUALIZAMOS EL ULTIMO LOGEO DEL USUARIO. 
					//$this->model_acceso->m_actualizar_fecha_ultima_sesion($arrPerfilUsuario);

					$arrData['message'] = 'Usuario inició sesión correctamente';
					if( isset($arrPerfilUsuario['idusuario']) ){ 
						$this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrPerfilUsuario);
					}else{
						$arrData['flag'] = 0;
	    				$arrData['message'] = 'No se encontró los datos del usuario.';
					}
				}elseif($loggedUser['estado_uw'] == 2){
					$arrData['flag'] = 2;
					$arrData['message'] = 'Su cuenta se encuentra deshabilitada. Debe verificarla mediante el email enviado.';
				} 
				
			}else{ 
    			$arrData['flag'] = 0;
    			$arrData['message'] = 'Usuario o contraseña invalida. Inténtelo nuevamente.';
    		}		
		}else{
			$arrData['flag'] = 0;
    		$arrData['message'] = 'No se encontraron datos.';
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function getSessionCI(){
		$arrData['flag'] = 0;
		$arrData['datos'] = array();
		if( $this->session->has_userdata( 'sess_cevs_'.substr(base_url(),-8,7) ) && 
			!empty($_SESSION['sess_cevs_'.substr(base_url(),-8,7) ]['idusuario']) ){
			$arrData['flag'] = 1;
			$arrData['datos'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];
			/*$arrParams['idusuario'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ]['idusuario'];
			$fila = $this->model_usuario->m_cargar_este_usuario($arrParams); */
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function logoutSessionCI(){
		$this->session->unset_userdata('sess_cevs_'.substr(base_url(),-8,7));
        //$this->cache->clean();
	}

	public function get_config(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['datos'] = getConfig($allInputs['tipo'],empty($allInputs['idsedeempresaadmin']) ? null : $allInputs['idsedeempresaadmin']);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function lista_notificaciones_eventos(){
		$sesion = $this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
		$arrListado = array();
		$arrListadoNoLeido = array();
		$arrData['flag'] = 0;
		// $arrData['count'] = 0;
	    $arrData['message'] = 'No hay notificaciones.';
	    $lista = $this->model_control_evento_web->m_cargar_notificaciones_usuario($sesion['idusuario']);
	    $contador = $this->model_control_evento_web->m_count_notificaciones_sin_leer_usuario($sesion['idusuario']); 
		foreach ($lista as $row) {
			$clase =  '';
			$icono =  '';
			$string =  '';
			$color_background = '';
			if($row['idtipoevento'] == 17){
				$clase = 'success';
				$icono =  'fa fa-plus';
				$string =  '';
			}else if($row['idtipoevento'] == 18){
				$clase = 'info';
				$icono =  'fa fa-calendar';
				$string =  '';
			}else if($row['idtipoevento'] == 19){
				$clase = 'warning';
				$icono =  'fa fa-users';
				$string =  '';
			}
			
			if($row['estado_uce'] == 2){
				$color_background = '#fafafa';
			}else{
				$color_background = 'rgb(220, 244, 247)';
			}			

			$array = array(
				'idusuariowebcontrolevento' => (int)$row['idusuariowebcontrolevento'],
				'idusuarioweb' => (int)$row['idusuarioweb'],
				'fecha_evento' => $row['fecha_evento'],				
				'fecha_evento_str' => date('d-m-Y H:i:s',strtotime($row['fecha_evento'])),
				'fecha_leido' => $row['fecha_leido'],				
				'fecha_leido_str' => empty($row['fecha_leido'])? NULL : date('d-m-Y',strtotime($row['fecha_leido'])),
				'estado_uce' => (int)$row['estado_uce'],				
				'idtipoevento' => (int)$row['idtipoevento'],
				'idresponsable' => (int)$row['idresponsable'],
				'identificador' => (int)$row['identificador'],
				'texto_notificacion' => $row['texto_notificacion'],
				'descripcion_te' => $row['descripcion_te'],
				'key_evento' => $row['key_evento'],
				'notificacion' =>$row['texto_notificacion'],
				'clase' => $clase,
				'icono' => $icono,
				'color_background' => $color_background,				
			);
			array_push($arrListado, $array);
			if($row['estado_uce'] == 1)
				array_push($arrListadoNoLeido, $array);
		}

	    if( !empty($lista) ){ 
			$arrData['flag'] = 1;
    		$arrData['message'] = 'Se cargaron las notificaciones';
		}else{
			$arrData['message'] = 'No hay notificaciones.';
		}
		$arrData['datos'] = $arrListado;
		$arrData['noLeidas'] = $arrListadoNoLeido;
    	$arrData['contador'] = $contador;
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}
