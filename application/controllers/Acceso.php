<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','otros_helper'));
		$this->load->model(array('model_acceso','model_usuario'));
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
					$arrPerfilUsuario['nombre_imagen'] = $perfil['nombre_imagen'];
					$arrPerfilUsuario['peso'] = $perfil['peso'];
				    $arrPerfilUsuario['estatura'] = $perfil['estatura'];
				    $arrPerfilUsuario['tipo_sangre']['id'] = empty($perfil['tipo_sangre']) ? null  :$perfil['tipo_sangre'];
				    $arrPerfilUsuario['tipo_sangre']['descripcion'] = empty($perfil['tipo_sangre']) ? null : $tipo_sangre[$perfil['tipo_sangre']] ;
					$arrPerfilUsuario['listaCitas'] =array();

					$paciente = ucwords(strtolower( $perfil['nombres'] . ' ' . 
											$perfil['apellido_paterno'] . ' ' . 
											$perfil['apellido_materno']));
					
					$arrPerfilUsuario['paciente'] = $paciente;
					
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
		$arrData['datos'] = getConfig('captcha');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}
