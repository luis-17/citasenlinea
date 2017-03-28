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

/*comentado hasta ver la forma q se pueda acceder en las vistas*/
/*function numberFormat(monto, decimales){

  monto += ''; // por si pasan un numero en vez de un string
  monto = parseFloat(monto.replace(/[^0-9\.\-]/g, '')); // elimino cualquier cosa que no sea numero o punto
  decimales = decimales || 0; // por si la variable no fue pasada
  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(monto) || monto === 0) 
      return parseFloat(0).toFixed(decimales);
  // si es mayor o menor que cero retorno el valor formateado como numero
  monto = '' + monto.toFixed(decimales);
  var monto_partes = monto.split('.'),
      regexp = /(\d+)(\d{3})/;
  while (regexp.test(monto_partes[0]))
      monto_partes[0] = monto_partes[0].replace(regexp, '$1' + ',' + '$2');
  return monto_partes.join('.');
}*/
appRoot = angular.module('theme.core.main_controller', ['theme.core.services', 'blockUI'])
  .controller('MainController', ['$scope', '$route', '$uibModal', '$document', '$theme', '$timeout', 'progressLoader', 'wijetsService', '$routeParams', '$location','$controller'
    , 'blockUI', 'uiGridConstants', 'pinesNotifications',
    function($scope, $route, $uibModal, $document, $theme, $timeout, progressLoader, wijetsService, $routeParams, $location, $controller
      , blockUI, uiGridConstants, pinesNotifications) {
    //'use strict';

    $scope.fAlert = {};
    $scope.arrMain = {};
    $scope.fSessionCI = {};
    $scope.fSessionCI.listaEspecialidadesSession = [];
    $scope.fSessionCI.listaNotificaciones = {};
    
    $scope.arrMain.sea = {};
    //$scope.listaEspecialidadesSession = [];
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
    $scope.getLayoutOption = function(key) {
      return $theme.get(key);
    };

    $scope.setNavbarClass = function(classname, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      $theme.set('topNavThemeClass', classname);
    };

    $scope.setSidebarClass = function(classname, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      $theme.set('sidebarThemeClass', classname);
    };

    $scope.$watch('layoutFixedHeader', function(newVal, oldval) {
      if (newVal === undefined || newVal === oldval) {
        return;
      }
      $theme.set('fixedHeader', newVal);
    });
    $scope.$watch('layoutLayoutBoxed', function(newVal, oldval) {
      if (newVal === undefined || newVal === oldval) {
        return;
      }
      $theme.set('layoutBoxed', newVal);
    });
    $scope.$watch('layoutLayoutHorizontal', function(newVal, oldval) {
      if (newVal === undefined || newVal === oldval) {
        return;
      }
      $theme.set('layoutHorizontal', newVal);
    });
    $scope.$watch('layoutPageTransitionStyle', function(newVal) {
      $theme.set('pageTransitionStyle', newVal);
    });
    $scope.$watch('layoutDropdownTransitionStyle', function(newVal) {
      $theme.set('dropdownTransitionStyle', newVal);
    });
    $scope.$watch('layoutLeftbarCollapsed', function(newVal, oldVal) {
      if (newVal === undefined || newVal === oldVal) {
        return;
      }
      $theme.set('leftbarCollapsed', newVal);
    });
    //$theme.set('leftbarCollapsed', false);
    $scope.toggleLeftBar = function() {
      $theme.set('leftbarCollapsed', !$theme.get('leftbarCollapsed'));
    };

    $scope.$on('themeEvent:maxWidth767', function(event, newVal) {
      $timeout(function() {
          $theme.set('leftbarCollapsed', newVal);
      });
    });
    $scope.$on('themeEvent:changed:fixedHeader', function(event, newVal) {
      $scope.layoutFixedHeader = newVal;
    });
    $scope.$on('themeEvent:changed:layoutHorizontal', function(event, newVal) {
      $scope.layoutLayoutHorizontal = newVal;
    });
    $scope.$on('themeEvent:changed:layoutBoxed', function(event, newVal) {
      $scope.layoutLayoutBoxed = newVal;
    });
    $scope.$on('themeEvent:changed:leftbarCollapsed', function(event, newVal) {
      $scope.layoutLeftbarCollapsed = newVal;
    });

    $scope.isLoggedIn = false;
    $scope.logOut = function() {
      $scope.isLoggedIn = false;
    };
    $scope.logIn = function() {
      $scope.isLoggedIn = true;
    };

    
    
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
   