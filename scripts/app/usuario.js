angular.module('theme.usuario', ['theme.core.services'])
  .controller('usuarioController', ['$scope', '$controller','$sce', '$filter','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'usuarioServices',
    'rootServices',
    function($scope, $controller, $sce, $filter, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      usuarioServices,
      rootServices
    ){ 
    'use strict'; 
    $scope.modulo = 'usuario';
    $scope.titleForm = 'Registro en Citas en Linea';
    //$scope.fDataUser = {}; 
    $scope.listaSexos = [
        {id:'-', descripcion:'SELECCIONE SEXO'},
        {id:'F', descripcion:'FEMENINO'},
        {id:'M', descripcion:'MASCULINO'}
      ];
    console.log('paso por aqui');
    $scope.fDataUsuario = {};
    $scope.init = function(){
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fDataUser = response.datos;
          $scope.fSessionCI = response.datos;
          if(!$scope.fSessionCI.nombre_imagen || $scope.fSessionCI.nombre_imagen === ''){
            $scope.fSessionCI.nombre_imagen = 'noimage.jpg';
          }
        }
      });
      $scope.selectedTab = '0';
      $controller('parienteController', { 
        $scope : $scope
      });
    }

    $scope.initRecaptchaReg = function () {
      grecaptcha.render('recaptcha-registro', {
        'sitekey' : $scope.keyRecaptcha,
        'callback' : recaptchaResponseReg,
      });
    }
    
    $scope.initRegistrarUsuario = function(){ 
      $scope.fDataUser = {}; 
      $scope.fDataUser.sexo = '-'; 
      $scope.captchaValidoReg = false;
             

      $scope.btnCancel = function(){
        $modalInstance.dismiss('btnCancel');
      }

      $scope.verificarDoc = function(){
        console.log("doc:",$scope.fDataUser);
        if(!$scope.fDataUser.num_documento || $scope.fDataUser.num_documento == null || $scope.fDataUser.num_documento == ''){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg='Debe ingresar un Número de documento.';
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation';
          return;
        }
        usuarioServices.sVerificarUsuarioPorDocumento($scope.fDataUser).then(function (rpta) {              
          $scope.fAlert = {};
          if( rpta.flag == 2 ){ //Cliente registrado en Sistema Hospitalario
            $scope.fDataUser = rpta.usuario;
            $scope.fAlert.type= 'info';
            $scope.fAlert.msg= rpta.message;
            $scope.fAlert.icon= 'fa fa-smile-o';
            $scope.fAlert.strStrong = 'Genial! ';
          }else if( rpta.flag == 1 ){ // Usuario ya registrado en web
            //$scope.fDataUser = rpta.usuario;
            $scope.fAlert.type= 'danger';
            $scope.fAlert.msg= rpta.message;
            $scope.fAlert.strStrong = 'Aviso! ';
            $scope.fAlert.icon = 'fa  fa-exclamation-circle';
          }else if(rpta.flag == 0){
            var num_documento = $scope.fDataUser.num_documento;                
            $scope.fAlert.type= 'warning';
            $scope.fAlert.msg= rpta.message;
            $scope.fAlert.strStrong = 'Aviso! ';
            $scope.fAlert.icon = 'fa fa-frown-o';
            $scope.fDataUser = {};
            $scope.fDataUser.num_documento = num_documento;
            $scope.fDataUser.sexo = '-';
          }
          $scope.fAlert.flag = rpta.flag;
        });
      }

      $scope.registrarUsuario = function (){
        if($scope.fDataUser.clave !== $scope.fDataUser.repeat_clave){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg='Las claves ingresadas no coinciden';
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation';
          return;
        }

        if($scope.fDataUser.sexo==='-'){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg='Las claves ingresadas no coinciden';
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation';
        }

        if(!$scope.captchaValidoReg){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= 'Debe completar reCaptcha';
          $scope.fAlert.strStrong = 'Error.';
          return;
        }

        usuarioServices.sRegistrarUsuario($scope.fDataUser).then(function (rpta) {       
          if(rpta.flag == 0){
            $scope.fAlert = {};
            $scope.fAlert.type= 'danger';
            $scope.fAlert.msg= rpta.message;
            $scope.fAlert.strStrong = 'Error';
            $scope.fAlert.icon = 'fa fa-exclamation';
          }else if(rpta.flag == 1){
            $scope.fDataUser = {};
            $scope.fDataUser.sexo = '-';
            $scope.fAlert.type= 'success';
            $scope.fAlert.msg= rpta.message;
            $scope.fAlert.icon= 'fa fa-smile-o';
            $scope.fAlert.strStrong = 'Genial! ';
          }
        });
      }   
    }

    $scope.closeAlert = function() {
        $scope.fAlert = null;
    }

    $scope.btnActualizarDatosCliente = function(){
      blockUI.start('Actualizando datos...');
      usuarioServices.sActualizarDatosCliente($scope.fDataUser).then(function (rpta) {       
        if(rpta.flag == 0){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= rpta.message;
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation'; 
          setTimeout(function() {
                $scope.closeAlert();
              }, 15000);        
        }else if(rpta.flag == 1){
          var msg =  rpta.message;
          usuarioServices.sRecargarUsuarioSession($scope.fDataUser).then(function (rpta) {
            if(rpta.flag == 1){
              $scope.init();
              $scope.fAlert = {};
              $scope.fAlert.type= 'success';
              $scope.fAlert.msg= msg;
              $scope.fAlert.icon= 'fa fa-smile-o';
              $scope.fAlert.strStrong = 'Genial! ';
              setTimeout(function() {
                $scope.closeAlert();
              }, 15000);
            } else{
              alert('Error inesperado');
            }           
          });
        }
        blockUI.stop();                
      });
    }

    $scope.btnActualizarClave = function (){
      $scope.closeAlertClave = function() {
        $scope.fAlertClave = null;
      }

      $scope.fDataUsuario.miclave = 'si';
      usuarioServices.sActualizarPasswordUsuario($scope.fDataUsuario).then(function (rpta){
        if(rpta.flag == 1){
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'success';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Genial.';          
        }else if(rpta.flag == 2){
          $scope.fDataUsuario.clave = null;
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'warning';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Advertencia.';
        }else if(rpta.flag == 0){
          $scope.fDataUsuario.claveNueva = null;
          $scope.fDataUsuario.claveConfirmar = null;
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'danger';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Error. ';
          setTimeout(function() {
            $('#nuevoPass').focus();
          }, 500);
        }else{
          alert('Error inesperado');
        } 

        setTimeout(function() {
            $scope.closeAlertClave();
          }, 1000);              
      });            
    }

    $scope.btnCambiarMiFotoPerfil = function (usuario, session){          
      $uibModal.open({
        templateUrl: angular.patchURLCI+'usuario/ver_popup_foto_perfil',
        controller: function ($scope, $modalInstance) {
          $scope.titleForm = 'Cambiar Foto de perfil';
          $scope.dataUsuario = usuario; 
          $scope.session = session;
          $scope.closeAlertSubida = function() {
            $scope.fAlertSubida = null;
          }

          $scope.aceptarSubida = function (){
            blockUI.start('Subiendo Archivo...');
            var formData = new FormData();                  
            angular.forEach($scope.fDataSubida,function (val,index) {              
              formData.append(index,val);
            });
            
            usuarioServices.sSubirFotoPerfil(formData).then(function (rpta) { 
              var nuevoArchivo = rpta.nuevoNombre;
              if(rpta.flag == 1){                
                $scope.cancelSubida();                
                usuarioServices.sRecargarUsuarioSession($scope.dataUsuario).then(function (rpta) {
                  if(rpta.flag == 1){
                    $scope.session.nombre_imagen = nuevoArchivo;
                    //$window.location.reload();
                  } else{
                    alert('Error inesperado');
                  }           
                });                
              }else if(rpta.flag == 0){
                $scope.fAlertSubida = {};
                $scope.fAlertSubida.type= 'warning';
                $scope.fAlertSubida.msg= rpta.message;
                $scope.fAlertSubida.strStrong = 'Advertencia.';
              }else{
                alert('Error inesperado');
              }

              blockUI.stop();
              setTimeout(function() {
                $scope.closeAlertSubida();
              }, 1000); 
            });
          }

          $scope.cancelSubida = function () {
            $modalInstance.dismiss('cancelSubida');
            $scope.fDataSubida = {};
          }
        }
      });
    }  
  }])
  .service("usuarioServices",function($http, $q) {
    return({
      sVerificarUsuarioPorDocumento:sVerificarUsuarioPorDocumento,
      sRegistrarUsuario: sRegistrarUsuario,
      sActualizarDatosCliente:sActualizarDatosCliente,
      sRecargarUsuarioSession: sRecargarUsuarioSession,
      sActualizarPasswordUsuario: sActualizarPasswordUsuario,
      sSubirFotoPerfil:sSubirFotoPerfil,
    });
    function sVerificarUsuarioPorDocumento(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/verificar_usuario_por_documento", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sRegistrarUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/registrar_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sActualizarDatosCliente(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/actualizar_datos_cliente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sRecargarUsuarioSession(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/recargar_usuario_session", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }     
    function sActualizarPasswordUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/actualizar_password_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sSubirFotoPerfil(pDatos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/subir_foto_perfil", 
            data : pDatos,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
      });
      return (request.then( handleSuccess,handleError ));
    }    

  }); 