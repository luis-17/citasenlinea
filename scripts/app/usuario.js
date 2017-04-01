angular.module('theme.usuario', ['theme.core.services'])
  .controller('usuarioController', ['$scope', '$sce', '$filter','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'usuarioServices',
    function($scope, $sce, $filter, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      usuarioServices
    ){ 
    'use strict'; 
    $scope.modulo = 'usuario';
    $scope.listaSexos = [
        {id:'-', descripcion:'SELECCIONE SEXO'},
        {id:'F', descripcion:'FEMENINO'},
        {id:'M', descripcion:'MASCULINO'}
      ];

      $scope.init = function(){
        $scope.fData = $scope.fSessionCI;
        $scope.selectedTab = '0';
        console.log($scope.fSessionCI);
      }
    
    $scope.btnRegistrarUsuario = function(){     
      $scope.fData = {}; 
      $scope.fData.sexo = '-'; 
      $scope.captchaValido = false;
      window.recaptchaResponse = function(key) {
        $scope.captchaValido = true;
      };

      blockUI.start('Abriendo formulario...');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Usuario/ver_popup_formulario',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Registro en Citas en Linea';     

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }

          $scope.verificarDoc = function(){
            if(!$scope.fData.num_documento || $scope.fData.num_documento == null || $scope.fData.num_documento == ''){
              $scope.fAlert = {};
              $scope.fAlert.type= 'danger';
              $scope.fAlert.msg='Debe ingresar un Número de documento.';
              $scope.fAlert.strStrong = 'Error';
              $scope.fAlert.icon = 'fa fa-exclamation';
              return;
            }
            usuarioServices.sVerificarUsuarioPorDocumento($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              if( rpta.flag == 2 ){ //Cliente registrado en Sistema Hospitalario
                $scope.fData = rpta.usuario;
                $scope.fAlert.type= 'info';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.icon= 'fa fa-smile-o';
                $scope.fAlert.strStrong = 'Genial! ';
              }else if( rpta.flag == 1 ){ // Usuario ya registrado en web
                //$scope.fData = rpta.usuario;
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Aviso! ';
                $scope.fAlert.icon = 'fa  fa-exclamation-circle';
              }else if(rpta.flag == 0){
                var num_documento = $scope.fData.num_documento;                
                $scope.fAlert.type= 'warning';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Aviso! ';
                $scope.fAlert.icon = 'fa fa-frown-o';
                $scope.fData = {};
                $scope.fData.num_documento = num_documento;
                $scope.fData.sexo = '-';
              }
              $scope.fAlert.flag = rpta.flag;
            });
          }

          $scope.registrarUsuario = function (){
            if($scope.fData.clave !== $scope.fData.repeat_clave){
              $scope.fAlert = {};
              $scope.fAlert.type= 'danger';
              $scope.fAlert.msg='Las claves ingresadas no coinciden';
              $scope.fAlert.strStrong = 'Error';
              $scope.fAlert.icon = 'fa fa-exclamation';
              return;
            }

            if($scope.fData.sexo==='-'){
              $scope.fAlert = {};
              $scope.fAlert.type= 'danger';
              $scope.fAlert.msg='Las claves ingresadas no coinciden';
              $scope.fAlert.strStrong = 'Error';
              $scope.fAlert.icon = 'fa fa-exclamation';
            }

            if(!$scope.captchaValido){
              $scope.fAlert = {};
              $scope.fAlert.type= 'danger';
              $scope.fAlert.msg= 'Debe completar reCaptcha';
              $scope.fAlert.strStrong = 'Error.';
              return;
            }

            usuarioServices.sRegistrarUsuario($scope.fData).then(function (rpta) {       
              if(rpta.flag == 0){
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error';
                $scope.fAlert.icon = 'fa fa-exclamation';
              }else if(rpta.flag == 1){
                $scope.fData = {};
                $scope.fData.num_documento = num_documento;
                $scope.fData.sexo = '-';
                $scope.fData = rpta.usuario;
                $scope.fAlert.type= 'success';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.icon= 'fa fa-smile-o';
                $scope.fAlert.strStrong = 'Genial! ';
              }
            });
          }   
          
          $scope.initRecaptcha = function () {
            grecaptcha.render('recaptcha-registro', {
              'sitekey' : $scope.keyRecaptcha,
              'callback' : recaptchaResponse,
            });
          };

          blockUI.stop();
        }
      });
      

    }
  
  }])
  .service("usuarioServices",function($http, $q) {
    return({
      sVerificarUsuarioPorDocumento:sVerificarUsuarioPorDocumento,
      sRegistrarUsuario: sRegistrarUsuario,
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

  }); 