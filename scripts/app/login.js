angular.module('theme.login', ['theme.core.services'])
  .controller('loginController', function($scope, $theme, $controller, loginServices, rootServices ){
    //'use strict';
    $theme.set('fullscreen', true);

    $scope.$on('$destroy', function() {
      $theme.set('fullscreen', false);
    });
    $scope.modulo='login';    

    $scope.initLoginRecaptcha = function() {      
      if(!$scope.keyRecaptcha  || $scope.keyRecaptcha == ''){
        rootServices.sGetConfig().then(function(rpta){
          $scope.keyRecaptcha =  rpta.datos.KEY_RECAPTCHA;
          console.log($scope.keyRecaptcha);
          //'6LeP4BoUAAAAAH7QZfe8sM5GAyVkMy1aak4Ztuhs'; //cambiar por servicio de configuracion
          grecaptcha.render('recaptcha-login', {
            'sitekey' : $scope.keyRecaptcha,
            'callback' : recaptchaResponse,
          });
        });
      }else{
        grecaptcha.render('recaptcha-login', {
          'sitekey' : $scope.keyRecaptcha,
          'callback' : recaptchaResponse,
        });
      }      
    };

    $scope.fLogin = {};
    
    $scope.logOut();
    $scope.btnLoginToSystem = function () {
      if($scope.fLogin.usuario == null || $scope.fLogin.clave == null){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe completar los campos usuario y clave.';
        $scope.fAlert.strStrong = 'Error.';
        return;
      }

      /*if(!$scope.captchaValido){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe completar reCaptcha';
        $scope.fAlert.strStrong = 'Error.';
        return;
      }*/

      loginServices.sLoginToSystem($scope.fLogin).then(function (response) { 
        $scope.fAlert = {};
        if( response.flag == 1 ){ // SE LOGEO CORRECTAMENTE 
          $scope.fAlert.type= 'success';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'OK.';
          $scope.getValidateSession();
          $scope.logIn();
          // $scope.getNotificaciones();
        }else if( response.flag == 0 ){ // NO PUDO INICIAR SESION 
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'Error.';
        }else if( response.flag == 2 ){  // CUENTA INACTIVA
          $scope.fAlert.type= 'warning';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'Información.';
          $scope.listaSedes = response.datos;
        }
        $scope.fAlert.flag = response.flag;
        //$scope.fLogin = {};
      });
    }
  })
  .service("loginServices",function($http, $q) {
    return({
        sLoginToSystem: sLoginToSystem
    });

    function sLoginToSystem(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });