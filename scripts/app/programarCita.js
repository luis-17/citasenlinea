angular.module('theme.programarCita', ['theme.core.services'])
  .controller('programarCitaController', ['$scope', '$controller', '$filter', '$sce', '$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys','blockUI', 
    'programarCitaServices',
    'sedeServices',
    'especialidadServices',
    'parienteServices',
    'rootServices',
    function($scope, $controller, $filter, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
      programarCitaServices,
      sedeServices,
      especialidadServices,
      parienteServices,
      rootServices
      ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'programarCita';
    
    $scope.fBusqueda = {};
    var fechaHasta = moment().add(6,'days');
    $scope.fBusqueda.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
    $scope.fBusqueda.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');
    $scope.fSeleccion = {};
    $scope.fPlanning = null; 
    $scope.fBusqueda.itemFamiliar = null; 
    $scope.listaEspecialidad = [
      { id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '}
    ];
    $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[0];

    var datos = {
      search:1,
      nameColumn:'tiene_prog_cita'
    };
    sedeServices.sListarSedesCbo(datos).then(function (rpta) {
      $scope.listaSedes = rpta.datos;
      $scope.listaSedes.splice(0,0,{ id : 0, idsede:0, descripcion:'SEDE'});
      $scope.fBusqueda.itemSede = $scope.listaSedes[0];
    });

    $scope.listarParientes = function(externo){
      parienteServices.sListarParientesCbo().then(function (rpta) {
        $scope.listaFamiliares = rpta.datos;
        $scope.listaFamiliares.splice(0,0,{ idusuariowebpariente:0, descripcion: $scope.fSessionCI.nombres + ' (titular)'});
        if(externo){          
          $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[$scope.listaFamiliares.length-1]; 
        }else{
          $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[0];
        }
      });
    }
    $scope.listarParientes();

    $scope.listarEspecialidad = function(){
      var datos = {
        idsede : $scope.fBusqueda.itemSede.id,
      }

      especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
        $scope.listaEspecialidad = rpta.datos;
        $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
        $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[0];
      });
    }

    $scope.getMedicoAutocomplete = function (value) {
      var params = $scope.fBusqueda;
      params.search= value;
      params.sensor= false;
        
      return programarCitaServices.sListarMedicosAutocomplete(params).then(function(rpta) { 
        $scope.noResultsLM = false;
        if( rpta.flag === 0 ){
          $scope.noResultsLM = true;
        }
        return rpta.datos; 
      });
    }

    $scope.getSelectedMedico = function($item, $model, $label){
      $scope.fBusqueda.itemMedico = $item;
    }

    $scope.cargarPlanning = function(){
      programarCitaServices.sCargarPlanning($scope.fBusqueda).then(function(rpta){
        $scope.fPlanning = rpta.planning;
      });
    }

    $scope.btnAgregarNuevoPariente = function(){
      var callback = function(){
        $scope.listarParientes(true);
      }

      $controller('parienteController', { 
        $scope : $scope
      });
      $scope.btnNuevoPariente(callback);
    }

    $scope.verTurnosDisponibles = function(item){
      blockUI.start('Abriendo formulario...');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_turnos',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                          
          $scope.titleForm = 'Turnos Disponibles'; 
          var datos = item;
          datos.medico = $scope.fBusqueda.itemMedico;
          programarCitaServices.sCargarTurnosDisponibles(datos).then(function(rpta){
            $scope.fPlanning.turnos=rpta.datos;
          });    

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }

          $scope.checkedCupo = function(cupo){
            $scope.fSeleccion = cupo; 
            cupo.checked=true;
          }

          $scope.btnReservarTurno = function(){            
            /*console.log($scope.fPlanning);
            console.log($scope.fBusqueda);
            console.log($scope.fSeleccion);*/
            var datos = {
              busqueda:angular.copy($scope.fBusqueda) ,
              seleccion:angular.copy($scope.fSeleccion)
            }

            $scope.fSessionCI.listaCitas.push(datos);           
            console.log($scope.fSessionCI.listaCitas);
            $scope.btnCancel();
          }
        
          blockUI.stop();
        }
      });
    }

    $scope.cambiarFechas = function() { 
      var fecha = moment($scope.fBusqueda.fecha).format('DD-MM-YYYY'); 
      var fechaHasta = moment($scope.fBusqueda.fecha).add(6,'days'); 
      fechaHasta = $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy'); 
      $scope.fBusqueda.desde = fecha;
      $scope.fBusqueda.hasta = fechaHasta;
      $scope.cargarPlanning();
    }

    $scope.resumenReserva = function(){
      programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
        $scope.goToUrl('/resumen-cita');
      });           
    }

    $scope.initResumenReserva = function(){
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
        }
        console.log($scope.fSessionCI);
      });
    }

    /* ============================ */
    /* ATAJOS DE TECLADO NAVEGACION */
    /* ============================ */
    hotkeys.bindTo($scope)
      .add({
        combo: 'alt+n',
        description: 'Nueva especialidad',
        callback: function() {
          $scope.btnNuevo();
        }
      })
      .add ({ 
        combo: 'e',
        description: 'Editar especialidad',
        callback: function() {
          if( $scope.mySelectionGrid.length == 1 ){
            $scope.btnEditar();
          }
        }
      })
      .add ({ 
        combo: 'del',
        description: 'Anular especialidad',
        callback: function() {
          if( $scope.mySelectionGrid.length > 0 ){
            $scope.btnAnular();
          }
        }
      })
      .add ({ 
        combo: 'b',
        description: 'Buscar especialidad',
        callback: function() {
          $scope.btnToggleFiltering();
        }
      })
      .add ({ 
        combo: 's',
        description: 'Selección y Navegación',
        callback: function() {
          $scope.navegateToCell(0,0);
        }
      });
  }])
  .service("programarCitaServices",function($http, $q) {
    return({
      sCargarPlanning:sCargarPlanning,
      sCargarTurnosDisponibles:sCargarTurnosDisponibles, 
      sListarMedicosAutocomplete:sListarMedicosAutocomplete,  
      sActualizarListaCitasSession:sActualizarListaCitasSession,
    });

    function sCargarPlanning(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cargar_planning", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sCargarTurnosDisponibles(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cargar_turnos_disponibles", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sListarMedicosAutocomplete(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/lista_medicos_autocomplete", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sActualizarListaCitasSession(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/actualizar_lista_citas_session", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
