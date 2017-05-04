<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ControlEventoWeb extends CI_Controller { 
	public function __construct()	{
		parent::__construct();
		$this->load->helper(array('security','otros_helper'));
		$this->load->model(array('model_control_evento_web'));
		
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

	public function update_leido_notificacion(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = '';
		$arrData['flag'] = 1;
		
		if($allInputs['estado_uce'] === 1){
			if(!$this->model_control_evento_web->m_update_leido_notificacion($allInputs['idusuariowebcontrolevento'])){
				$arrData['message'] = 'Ha ocurrido un error. Intente nuevamente';
				$arrData['flag'] = 0;
			}
		}				

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_notificacion_evento(){
		$this->load->view('control-evento/viewDetalleNotificacion_formView');
	}
}