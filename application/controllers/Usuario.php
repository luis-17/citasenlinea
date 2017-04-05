<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'otros_helper','imagen_helper'));
		$this->load->model(array('model_usuario'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
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
  			'nombre_usuario' => $allInputs['num_documento'],
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

  public function actualizar_datos_cliente(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrData['message'] = 'Los datos no han sido actualizados. Intente nuevamente';
    $arrData['flag'] = 0;

    if(empty($allInputs['sexo']) || empty($allInputs['sexo']) || empty($allInputs['sexo'])){
      $arrData['message'] = 'Debes ingresar nombres y apellidos.';
      $arrData['flag'] = 0;    
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($arrData));
        return;       
    } 

    if($allInputs['sexo'] == '-'){
      $arrData['message'] = 'Debes seleccionar Sexo.';
      $arrData['flag'] = 0;    
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($arrData));
        return;       
    } 

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

    //idcliente
    if($this->model_usuario->m_update_cliente($datos, (int)$allInputs['idcliente'])){
      $arrData['message'] = 'Los datos han sido actualizados correctamente.';
      $arrData['flag'] = 1;
    }  

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function recargar_usuario_session(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrPerfilUsuario = array();
    if(empty($allInputs['nombre_usuario'])){
      $allInputs['nombre_usuario'] = $this->sessionCitasEnLinea['nombre_usuario'];
    }
    $perfil = $this->model_usuario->m_cargar_usuario($allInputs);

    $arrPerfilUsuario['nombre_usuario'] = $perfil['nombre_usuario'];
    $arrPerfilUsuario['idusuario'] = $perfil['idusuarioweb'];
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

    $paciente = ucwords(strtolower( $perfil['nombres'] . ' ' . 
                $perfil['apellido_paterno'] . ' ' . 
                $perfil['apellido_materno']));
    
    $arrPerfilUsuario['paciente'] = $paciente;

    if( isset($arrPerfilUsuario['idusuario']) ){ 
      $arrData['flag'] = 1;
      $this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrPerfilUsuario);
    }else{
      $arrData['flag'] = 0;
    }
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function ver_popup_password(){
    $this->load->view('usuario/popupCambiarPassword_formView');
  }

  public function actualizar_password_usuario(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrData['message'] = 'No se pudo actualizar la contraseña. Intente nuevamente.';
    $arrData['flag'] = 0;   

    if(empty($allInputs['claveNueva']) || empty($allInputs['claveConfirmar'])){
      $arrData['message'] = 'Debe completar los campos Nueva contraseña y confirmación.';
      $arrData['flag'] = 0;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));  
      return;
    }

    if($allInputs['claveNueva'] != $allInputs['claveConfirmar']){
      $arrData['message'] = 'Las contraseñas no coinciden.';
      $arrData['flag'] = 0;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));  
      return;
    }

    $allInputs ['idusuario'] = $this->sessionCitasEnLinea['idusuario'];
    $usuario = $this->model_usuario->m_verifica_password($allInputs);
    if(empty($usuario)){
      $arrData['message'] = 'La constraseña actual es invalida.';
      $arrData['flag'] = 2;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));  
      return;
    }

    //si llega hasta aqui puede actualizar clave
    if($this->model_usuario->m_actualizar_password($allInputs)){
      $arrData['message'] = 'La contraseña fue actualizada correctamente.';
      $arrData['flag'] = 1;   
    }

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($arrData));  
  }

  public function ver_popup_foto_perfil(){
    $this->load->view('usuario/popupSubirFotoPerfil_formView');
  }

  public function subir_foto_perfil(){
    $allInputs['idusuario'] = (int)$this->sessionCitasEnLinea['idusuario'];
    $arrData['message'] = 'Error al subir los archivos, PESO MÁXIMO: 1MB';
    $arrData['flag'] = 0;

    if(empty($_FILES) ){
      $arrData['message'] = 'No se ha cargado ningun archivo. Cargue el archivo por favor.';
      $arrData['flag'] = 0;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;
    }
    //print_r($_FILES);
    if($_FILES['archivo']['size'] > 1000000 ){
      $arrData['message'] = 'Error al subir los archivos, PESO MÁXIMO: 1MB.';
      $arrData['flag'] = 0;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;
    }

    $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
    $allInputs['nuevoNombreArchivo'] = 'imgPerfil_'.date('YmdHis').'_'.$allInputs['idusuario'].'.'.$extension;
    //print_r($allInputs);

    if( subir_fichero('assets/img/dinamic/usuario/','archivo',$allInputs['nuevoNombreArchivo']) ){ 
      $allInputs['nombre_archivo'] = $_FILES['archivo']['name'];       
      if($this->model_usuario->m_subir_foto_perfil($allInputs)){ 
        $arrData['message'] = 'Se subió la imagen correctamente.'; 
        $arrData['flag'] = 1; 
        $arrData['nuevoNombre'] = $allInputs['nuevoNombreArchivo'];
      }
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  } 

}
