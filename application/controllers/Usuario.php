<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'otros_helper'));
		$this->load->model(array('model_usuario'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		
	}

	public function ver_popup_formulario(){
		$this->load->view('usuario/usuario_formView');
	}

	public function verificar_usuario_por_documento(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true); 		
		$arrData['message'] = 'Aún no eres nuestro paciente. Por favor, ingresa tus datos para finalizar el registro.';
    	$arrData['flag'] = 0;
    	$usuario = $this->model_usuario->m_cargar_por_documento($allInputs);

    	if(!empty($usuario)){
    		$usuario['fecha_nacimiento'] = Date('d-m-Y',strtotime($usuario['fecha_nacimiento']));
    		$arrData['usuario'] = $usuario;
    		$arrData['message'] = 'Ya eres nuestro paciente. Por favor, completa tus datos para finalizar el registro.';
    		$arrData['flag'] = 2;

    		if(!empty($usuario['idusuarioweb'])){
    			$arrData['message'] = 'Estimado paciente, ya estás registrado en nuestro sistema en linea. Intenta iniciar sesión.';
    			$arrData['flag'] = 1;
    		}    		
    	}   	

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function registrar_usuario(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'No se pudo finalizar el registro. Intente nuevamente.';
    	$arrData['flag'] = 0;

    	if(empty($allInputs['num_documento'])){
			$arrData['message'] = 'Debe ingresar un Número de documento.';
			$arrData['flag'] = 0;    
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;		 		
    	} 

    	$usuario = $this->model_usuario->m_verificar_email($allInputs);
    	if(!empty($usuario)){
    		$arrData['message'] = 'Estimado paciente, ese Email ya está registrado en nuestro sistema citas en linea. Intenta iniciar sesión.';
			$arrData['flag'] = 0;    
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;
    	}

    	$usuario = $this->model_usuario->m_cargar_por_documento($allInputs);
    	if(!empty($usuario) && !empty($usuario['idusuarioweb'])){
			$arrData['message'] = 'Estimado paciente, ya estás registrado en nuestro sistema citas en linea. Intenta iniciar sesión.';
			$arrData['flag'] = 0;    
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;		 		
    	} 

    	$resultCliente = FALSE;
    	$allInputs['telefono'] = (!isset($allInputs['telefono']) || empty($allInputs['telefono']) ) ? null : $allInputs['telefono'];
    	if(empty($usuario)){
   			//registrar usuario
   			$datos = array(
   				'num_documento' => $allInputs['num_documento'], 
   				'nombres' => strtoupper($allInputs['nombres']), 
   				'apellido_paterno' => strtoupper($allInputs['apellido_paterno']), 
   				'apellido_materno' => strtoupper($allInputs['apellido_materno']), 
   				'email' => $allInputs['email'], 
   				'sexo' => $allInputs['sexo'], 
   				'fecha_nacimiento' => $allInputs['fecha_nacimiento'], 
   				'celular' => $allInputs['celular'], 
   				'telefono' => $allInputs['telefono'], 
   				'createdAt' => date('Y-m-d H:i:s'),
   				'updatedAt' => date('Y-m-d H:i:s')
   				);
   			$resultCliente = $this->model_usuario->m_registrar_cliente($datos);
   			$idcliente = GetLastId('idcliente','cliente');
    	}else{
    		//actualizar usuario
    		$datos = array(
   				'nombres' => strtoupper($allInputs['nombres']), 
   				'apellido_paterno' => strtoupper($allInputs['apellido_paterno']), 
   				'apellido_materno' => strtoupper($allInputs['apellido_materno']), 
   				'email' => $allInputs['email'], 
   				'sexo' => $allInputs['sexo'], 
   				'fecha_nacimiento' => $allInputs['fecha_nacimiento'], 
   				'celular' => $allInputs['celular'], 
   				'telefono' => $allInputs['telefono'], 
   				'updatedAt' => date('Y-m-d H:i:s')
   				);
    		$idcliente =  $usuario['idcliente'];
    		$resultCliente = $this->model_usuario->m_update_cliente($datos, $idcliente);    		
    	} 

    	$resultUsuario = FALSE;
    	if($resultCliente){
    		//ingreso usuario
    		$datos = array(
    			'nombre_usuario' => $allInputs['email'],
    			'password' => do_hash($allInputs['clave'],'md5'), 
    			'idcliente' => $idcliente,
    			'createdAt' => date('Y-m-d H:i:s'),
   				'updatedAt' => date('Y-m-d H:i:s')
    			);
    		//$resultUsuario = $this->model_usuario->m_registrar_usuario($datos);
    	}

    	$resultUsuario = TRUE;
    	if($resultUsuario && $resultCliente){
    		/*ENVIAR CORREO PARA VERIFICAR*/
        $idusuarioweb = GetLastId('idusuarioweb','ce_usuario_web');
    		$listaDestinatarios = array();
    		array_push($listaDestinatarios, $allInputs['email']);
    		$paciente = ucwords(strtolower( $allInputs['nombres'] . ' ' . 
											$allInputs['apellido_paterno'] . ' ' . 
											$allInputs['apellido_materno']));

    		$setFromAleas = 'Villa Salud';
    		$subject = 'Confirma tu cuenta de Villa Salud';
    		$cuerpo = '<html> 
					      <body style="font-family: sans-serif;padding: 10px 40px;" > 
					        <div style="text-align: right;">
					          <img style="width: 160px;" alt="Hospital Villa Salud" src="'.base_url(). 'assets/img/dinamic/empresa/gm_small.png">
					        </div> <br />';
		  	$cuerpo .= '	<div style="font-size:16px;">  
		                		Estimado(a) paciente: '.$paciente .', <br /> <br /> ';
		    $cuerpo .= '		<a href="'. base_url() .'ci.php/usuario/setCuentaUsuario?data='. base64_encode($idusuarioweb) .'">Haz clic aquí para continuar con el proceso de registro.</a>';
		  	$cuerpo .= 		'</div>';


		  	$cuerpo .= '<div>
		  					<p>Si no has solicitado la suscripción a este correo electrónico, ignóralo y la suscripción no se activará.</p>
		  				</div>';

		  	$cuerpo .= '<div >
		  					<p>Si no has solicitado la suscripción a este correo electrónico, ignóralo y la suscripción no se activará.</p>
		  				</div>';

		  	$cuerpo .= '</body>';
		  	$cuerpo .= '</html>';

		  	$result = enviar_mail($subject, $setFromAleas,$cuerpo,$listaDestinatarios);
		  	//print_r($result);
		  	$arrData['flagMail'] = $result[0]['flag'];
		  	if($result[0]['flag'] == 1){
		  		$arrData['message'] = 'El registro fue satisfactorio. Recibirás un mensaje en el correo para verificar la cuenta.';
    			$arrData['flag'] = 1;
		  	}    		
    	}

    	$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));	
	}

	public function setCuentaUsuario(){
		$allInputs = $_GET['data'];
    $id = base64_decode($allInputs); //utilizar base64_encode en el envio de email

    if(is_numeric($id)){
      $data = array (
        'estado_uw' => 1,
        'updatedAt' => date('Y-m-d H:i:s')
      );
      
      if($this->model_usuario->m_update_estado_usuario($data, $id)){
        $this->load->view('usuario/verificacion-cuenta');
      }else{
        $this->load->view('usuario/error-verificacion-cuenta');
      } 
    }else{
      $this->load->view('usuario/error-verificacion-cuenta');
    }       
	}
}
