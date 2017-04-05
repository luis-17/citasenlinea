angular.patchURL = dirWebRoot;
angular.patchURLCI = dirWebRoot+'ci.php/';
angular.dirViews = angular.patchURL+'/application/views/';
function handleError( response ) {
    if ( ! angular.isObject( response.data ) || ! response.data.message ) {
        return( $q.reject( "An unknown error occurred." ) );
    }
    return( $q.reject( response.data.message ) );
}
function handleSuccess( response ) {
    return( response.data );
}
function redondear(num, decimal){
  var decimal = decimal || 2;
  if (isNaN(num) || num === 0){
    return parseFloat(0);
  }
  var factor = Math.pow(10,decimal);
  return Math.round(num * factor ) / factor;
}

function newNotificacion(body,icon,title,tag) {
  var options = {
      body: body,
      icon: icon,
      tag: tag
  }

  var n = new Notification(title,options); 
  //console.log('se creo', n); 
}

appRoot = angular.module('theme.core.main_controller', ['theme.core.services', 'blockUI'])
  .controller('MainController', ['$scope', '$route', '$uibModal', '$document', '$theme', '$timeout', 'progressLoader', 'wijetsService', '$routeParams', '$location','$controller'
    , 'blockUI', 'uiGridConstants', 'pinesNotifications',
    'rootServices',
    'usuarioServices',
    function($scope, $route, $uibModal, $document, $theme, $timeout, progressLoader, wijetsService, $routeParams, $location, $controller
      , blockUI, uiGridConstants, pinesNotifications,
      rootServices,
      usuarioServices) {
    //'use strict';
    $scope.fAlert = {};
    $scope.arrMain = {};
    $scope.fSessionCI = {};
    $scope.fSessionCI.listaEspecialidadesSession = [];
    $scope.fSessionCI.listaNotificaciones = {};
    
    $scope.arrMain.sea = {};
    $scope.localLang = {
      selectAll       : "Seleccione todo",
      selectNone      : "Quitar todo",
      reset           : "Resetear todo",
      search          : "Escriba aquí para buscar...",
      nothingSelected : "No hay items seleccionados"
    };
    $scope.layoutFixedHeader = $theme.get('fixedHeader');
    $scope.layoutPageTransitionStyle = $theme.get('pageTransitionStyle');
    $scope.layoutDropdownTransitionStyle = $theme.get('dropdownTransitionStyle');
    $scope.layoutPageTransitionStyleList = ['bounce',
      'flash',
      'pulse',
      'bounceIn',
      'bounceInDown',
      'bounceInLeft',
      'bounceInRight',
      'bounceInUp',
      'fadeIn',
      'fadeInDown',
      'fadeInDownBig',
      'fadeInLeft',
      'fadeInLeftBig',
      'fadeInRight',
      'fadeInRightBig',
      'fadeInUp',
      'fadeInUpBig',
      'flipInX',
      'flipInY',
      'lightSpeedIn',
      'rotateIn',
      'rotateInDownLeft',
      'rotateInDownRight',
      'rotateInUpLeft',
      'rotateInUpRight',
      'rollIn',
      'zoomIn',
      'zoomInDown',
      'zoomInLeft',
      'zoomInRight',
      'zoomInUp'
    ];
    $scope.dirImages = angular.patchURL+'/assets/img/';
    $scope.layoutLoading = true;
    $scope.blockUI = blockUI;

    $scope.$on('$routeChangeStart', function() {
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          if ($location.path() === '') {            
            return $location.path('/');
          }

          if($location.path() !== '/login'){
            $scope.logIn();
          }          
        }else{
          $scope.goToUrl('/login');
        }
      });
      progressLoader.start();
      progressLoader.set(50);
    });

    $scope.$on('$routeChangeSuccess', function() {
      progressLoader.end();
      if ($scope.layoutLoading) {
        $scope.layoutLoading = false;
      }
      wijetsService.make();
    });

    $scope.keyRecaptcha = '6LeP4BoUAAAAAH7QZfe8sM5GAyVkMy1aak4Ztuhs'; //cambiar por servicio de configuracion
    $scope.captchaValido = false;
    window.recaptchaResponse = function(key) {
      $scope.captchaValido = true;
    };
 
    $scope.getLayoutOption = function(key) {
      return $theme.get(key);
    };

    $scope.isLoggedIn = false;
    $scope.logOut = function() {
      $scope.isLoggedIn = false;
      $scope.captchaValido = false;
    };
    $scope.logIn = function() {
      $scope.isLoggedIn = true;
    };

    $scope.goToUrl = function ( path ) {
      $location.path( path );
    };
    $scope.btnLogoutToSystem = function () {
      rootServices.sLogoutSessionCI().then(function () {
        $scope.fSessionCI = {};
        $scope.listaUnidadesNegocio = {};
        $scope.listaModulos = {};
        $scope.logOut();
        $scope.goToUrl('/login');
      });
    }

    $scope.getValidateSession = function () {
      rootServices.sGetSessionCI().then(function (response) {
        console.log(response);
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
          if(!$scope.fSessionCI.nombre_imagen || $scope.fSessionCI.nombre_imagen == ''){
            $scope.fSessionCI.nombre_imagen = 'noimage.jpg';
          }
          $scope.logIn();
          if( $location.path() == '/login' ){
            $scope.goToUrl('/');
          }
        }else{
          $scope.fSessionCI = {};
          $scope.logOut();
          $scope.goToUrl('/login');
        }
      });
    }   
    
    $scope.btnCambiarMiClave = function (size){
      $uibModal.open({
        templateUrl: angular.patchURLCI+'usuario/ver_popup_password',
        size: size || 'sm',
        controller: function ($scope, $modalInstance) {
          $scope.titleForm = 'Cambiar Contraseña';
          $scope.aceptar = function (){            
            $scope.fDataUsuario.miclave = 'si';
            usuarioServices.sActualizarPasswordUsuario($scope.fDataUsuario).then(function (rpta){
              if(rpta.flag == 1){
                $scope.fAlert = {};
                $scope.fAlert.type= 'success';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Genial.';
                setTimeout(function() {
                  $scope.cancel();
                }, 1000);
              }else if(rpta.flag == 2){
                $scope.fDataUsuario.clave = null;
                $scope.fAlert = {};
                $scope.fAlert.type= 'warning';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Advertencia.';
              }else if(rpta.flag == 0){
                $scope.fDataUsuario.claveNueva = null;
                $scope.fDataUsuario.claveConfirmar = null;
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error. ';
                setTimeout(function() {
                  $('#nuevoPass').focus();
                }, 500);
              }else{
                alert('Error inesperado');
              }               
            });            
          }

          $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
            $scope.fDataUsuario = {};
          }
        }
      });
    }
    /* END */
  }])
  .service("rootServices", function($http, $q) {
    return({
        sGetSessionCI: sGetSessionCI,
        sLogoutSessionCI: sLogoutSessionCI,
    });
    function sGetSessionCI() {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/getSessionCI"
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sLogoutSessionCI() {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/logoutSessionCI"
      });
      return (request.then( handleSuccess,handleError ));
    }   
  });
/* DIRECTIVAS */
appRoot.
  directive('ngEnter', function() {
    return function(scope, element, attrs) {
      element.bind("keydown", function(event) {

          if(event.which === 13) {
            //event.preventDefault();
            scope.$apply(function(){
              scope.$eval(attrs.ngEnter);
            });
            //event.stopPropagation();
          }
          //event.stopPropagation();
          //event.preventDefault();
      });
    };
  })
  .directive('scroller', function() {
    return {
      restrict: 'A',
      link: function(scope,elem,attrs){
          $(elem).on('scroll', function(evt){ 
            // PROGRAMACION DE AMBIENTES 
            $('.planning .sidebar .table').css('margin-top', -$(this).scrollTop());
            $('.planning .header .table').css('margin-left', -$(this).scrollLeft());
            // PROGRAMACION DE MEDICOS 
            $('.planning-medicos .fixed-row').css('margin-left', -$(this).scrollLeft());
            $('.planning-medicos .fixed-column').css('margin-top', -$(this).scrollTop()); 

            $('.planning-medicos .fixed-row .cell-planing.ambiente').css('left', $(this).scrollLeft()); 
            
          });
      }
    }
  })
  .directive('resetscroller', function() {
    return {
      restrict: 'A',
      link: function(scope,elem,attrs){
          $(elem).on('click', function(evt){ 
            // PROGRAMACION DE AMBIENTES 
            $('.planning .sidebar .table').css('margin-top', 0);
            $('.planning .header .table').css('margin-left', 0);
            $('.planning .body').scrollLeft(0);
            $('.planning .body').scrollTop(0);
            // PROGRAMACION DE MEDICOS 
            $('.planning-medicos .fixed-row').css('margin-left', -$(this).scrollLeft());
            $('.planning-medicos .fixed-column').css('margin-top', -$(this).scrollTop()); 

            $('.planning-medicos .fixed-row .cell-planing.ambiente').css('left', $(this).scrollLeft()); 
            
          });
      }
    }
  })
  .directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;
          element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
          });
        }
    };
  }])
  .directive('focusMe', function($timeout, $parse) {
    return {
      link: function(scope, element, attrs) {
        var model = $parse(attrs.focusMe);

        scope.$watch(model, function(pValue) {
            value = pValue || 0;
            $timeout(function() {
              element[value].focus();
              // console.log(element[value]);
            });
        });
      }
    };
  })
  .directive('stringToNumber', function() {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModel) {
        // console.log(scope);
        ngModel.$parsers.push(function(value) {
          // console.log('p '+value);
          return '' + value;
        });
        ngModel.$formatters.push(function(value) {
          // console.log('f '+value);
          return parseFloat(value, 10);
        });
      }
    };
  })
  .directive('enterAsTab', function () {
    return function (scope, element, attrs) {
      element.bind("keydown keypress", function (event) {
        if(event.which === 13 || event.which === 40) {
          event.preventDefault();
          var fields=$(this).parents('form:eq(0),body').find('input, textarea, select');
          var index=fields.index(this);
          if(index > -1 &&(index+1) < (fields.length - 1))
            fields.eq(index+1).focus();
        }
        if(event.which === 38) {
          event.preventDefault();
          var fields=$(this).parents('form:eq(0),body').find('input, textarea, select');
          var index=fields.index(this);
          if((index-1) > -1 && index < fields.length)
            fields.eq(index-1).focus();
        }
      });
    };
  })
  .directive('hcChart', function () {
      return {
          restrict: 'E',
          template: '<div></div>',
          scope: {
              options: '='
          },
          link: function (scope, element) {
            // scope.$watch(function () {
            //   return attrs.chart;
            // }, function () {
            //     if (!attrs.chart) return;
            //     var charts = JSON.parse(attrs.chart);
            //     $(element[0]).highcharts(charts);                
                Highcharts.chart(element[0], scope.options);
            // });

          }
      };
  })
  .directive('smartFloat', function() {
    var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
    return {
      require: 'ngModel',
      link: function(scope, elm, attrs, ctrl) {
        ctrl.$parsers.unshift(function(viewValue) {
          if (FLOAT_REGEXP.test(viewValue)) {
            ctrl.$setValidity('float', true);
            if(typeof viewValue === "number")
              return viewValue;
            else
              return parseFloat(viewValue.replace(',', '.'));
          } else {
            ctrl.$setValidity('float', false);
            return undefined;
          }
        });
      }
    };
  })
  .config(function(blockUIConfig) {
    blockUIConfig.message = 'Cargando datos...';
    blockUIConfig.delay = 0;
    blockUIConfig.autoBlock = false;
    //i18nService.setCurrentLang('es');
  })
  .filter('getRowSelect', function() {
    return function(arraySelect, item) {
      var fSelected = {};
      angular.forEach(arraySelect,function(val,index) {
        if( val.id == item ){
          fSelected = val;
        }
      })
      return fSelected;
    }
  })
  .filter('numberFixedLen', function () {
    return function (n, len) {
      var num = parseInt(n, 10);
      len = parseInt(len, 10);
      if (isNaN(num) || isNaN(len)) {
        return n;
      }
      num = ''+num;
      while (num.length < len) {
        num = '0'+num;
      }
      return num;
    };
  })
  .filter('griddropdown', function() {
    return function (input, context) {
      var map = context.col.colDef.editDropdownOptionsArray;
      var idField = context.col.colDef.editDropdownIdLabel;
      var valueField = context.col.colDef.editDropdownValueLabel;
      var initial = context.row.entity[context.col.field];
      if (typeof map !== "undefined") {
        for (var i = 0; i < map.length; i++) {
          if (map[i][idField] == input) {
            return map[i][valueField];
          }
        }
      } else if (initial) {
        return initial;
      }
      return input;
    };
  });

  // Prevent the backspace key from navigating back.
$(document).unbind('keydown').bind('keydown', function (event) {
  var doPrevent = false;
  if (event.keyCode === 8) {
    var d = event.srcElement || event.target;
    if((d.tagName.toUpperCase() === 'INPUT' &&
         (
             d.type.toUpperCase() === 'TEXT' ||
             d.type.toUpperCase() === 'PASSWORD' ||
             d.type.toUpperCase() === 'FILE' ||
             d.type.toUpperCase() === 'SEARCH' ||
             d.type.toUpperCase() === 'EMAIL' ||
             d.type.toUpperCase() === 'NUMBER' ||
             d.type.toUpperCase() === 'TEL' ||
             d.type.toUpperCase() === 'DATE' )
        ) ||
        d.tagName.toUpperCase() === 'TEXTAREA'
    ){
      doPrevent = d.readOnly || d.disabled;
    }
    else {
        doPrevent = true;
    }
  }

  if (doPrevent) {
      event.preventDefault();
  }
});
   