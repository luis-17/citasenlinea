angular.module('theme.historialCitas', ['theme.core.services'])
  .controller('historialCitasController', function($scope, $controller, $filter, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
    historialCitasServices,
    sedeServices,
    especialidadServices,
    parienteServices,
    rootServices,
    programarCitaServices,
    ventaServices
     ){
      'use strict';
      shortcut.remove("F2"); 
      $scope.modulo = 'historialCitas'; 
      $scope.dirComprobantes = 'https://citasenlinea.villasalud.pe/comprobantesWeb/';

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
        }
      });

      $scope.fBusqueda = {};
      $scope.listaTipoCita = [
        {id:'P', descripcion:'CITAS PENDIENTES'},
        {id:'R', descripcion:'CITAS REALIZADAS'}
      ]
      $scope.fBusqueda.tipoCita = $scope.listaTipoCita[0];
      var fechaHasta = moment().add(6,'days');
      $scope.fBusqueda.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
      $scope.fBusqueda.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');

      var datos = {
        search:1,
        nameColumn:'tiene_prog_cita'
      };
      sedeServices.sListarSedesCbo(datos).then(function (rpta) {
        $scope.listaSedes = rpta.datos;
        $scope.listaSedes.splice(0,0,{ id : 0, idsede:0, descripcion:'SEDE'});
        $scope.fBusqueda.sede = $scope.listaSedes[0];
      });

      $scope.listarParientes = function(externo){
        parienteServices.sListarParientesCbo().then(function (rpta) {
          $scope.listaFamiliares = rpta.datos;
          $scope.listaFamiliares.splice(0,0,{ idusuariowebpariente:0, descripcion: $scope.fSessionCI.nombres + ' [TITULAR]'});
          if(externo){          
            $scope.fBusqueda.familiar = $scope.listaFamiliares[$scope.listaFamiliares.length-1]; 
          }else{
            $scope.fBusqueda.familiar = $scope.listaFamiliares[0];
          }
        });
      }
      $scope.listarParientes();

      $scope.listaEspecialidad = [
        { id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '}
      ];
      $scope.fBusqueda.especialidad = $scope.listaEspecialidad[0];

      $scope.listarEspecialidad = function(){
        var datos = {
          idsede : $scope.fBusqueda.sede.id,
        }

        especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
          $scope.listaEspecialidad = rpta.datos;
          $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
          $scope.fBusqueda.especialidad = $scope.listaEspecialidad[0];
        });
      }

      $scope.listarHistorial = function(){
        historialCitasServices.sCargarHistorialCitas($scope.fBusqueda).then(function(rpta){          
          $scope.listaDeCitas = rpta.datos;        
        });
      }
      $scope.listarHistorial();

      $scope.reprogramarCita = function(cita){        
        programarCitaServices.sVerificaEstadoCita(cita).then(function(rpta){
          if(rpta.flag == 1){
            $scope.viewPlanning(cita);
          }else if(rpta.flag == 0){
            $uibModal.open({ 
              templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
              size: 'sm',
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = 'Aviso'; 
                $scope.msj = rpta.message;

                $scope.btnCancel = function(){
                  $modalInstance.dismiss('btnCancel');
                }
              }
            });
          }else{
            alert('Error inesperado');
          }
        });

        $scope.viewPlanning = function(cita){
          $uibModal.open({ 
            templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_planning',
            size: 'xlg',
            //backdrop: 'static',
            //keyboard:false,
            scope: $scope,
            controller: function ($scope, $modalInstance) {
              $scope.formats = ['dd-MM-yyyy','dd-MMMM-yyyy','yyyy/MM/dd','dd.MM.yyyy','shortDate'];
              $scope.format = $scope.formats[0]; // formato por defecto
              $scope.datePikerOptions = {
                formatYear: 'yy',
                // startingDay: 1,
                'show-weeks': false
              };

              $scope.openDP = function($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.opened = true;
              }
              
              $scope.fBusquedaRep = {};
              $scope.fBusquedaPlanning = {};
              $scope.fBusquedaPlanning = cita;

              angular.forEach($scope.listaFamiliares, function(value, key) {
                if(value.idusuariowebpariente == $scope.fBusquedaPlanning.itemFamiliar.idusuariowebpariente){
                  $scope.fBusquedaRep.itemFamiliar = $scope.listaFamiliares[key];
                }                
              });

              angular.forEach($scope.listaSedes, function(value, key) {
                if(value.id == $scope.fBusquedaPlanning.itemSede.id){
                  $scope.fBusquedaRep.itemSede = $scope.listaSedes[key];
                }                
              });

              var datos = {
                idsede : $scope.fBusquedaRep.itemSede.id,
              }
              especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
                $scope.listaEspecialidadRep = rpta.datos;
                $scope.listaEspecialidadRep.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
                angular.forEach($scope.listaEspecialidadRep, function(value, key) {
                  if(value.id == $scope.fBusquedaPlanning.itemEspecialidad.id){
                    $scope.fBusquedaRep.itemEspecialidad = $scope.listaEspecialidadRep[key];
                  }                
                });
              });

              var fechaHasta = moment().add(6,'days');
              $scope.fBusquedaPlanning.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
              $scope.fBusquedaPlanning.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');              

              $scope.btnCancel = function(){
                $modalInstance.dismiss('btnCancel');
              }

              $scope.cargarPlanning = function(){
                programarCitaServices.sCargarPlanning($scope.fBusquedaPlanning).then(function(rpta){
                  $scope.fPlanning = rpta.planning;
                });
              }
              $scope.cargarPlanning();

              $scope.viewTurnos = function(item){
                $scope.fPlanning.citas = {};
                $scope.fPlanning.citas.oldCita = cita;
                var callback = function (){
                  $scope.btnCancel();
                  $scope.listarHistorial();
                  $scope.getNotificacionesEventos();
                }
                $controller('programarCitaController', { 
                  $scope : $scope
                });
                $scope.verTurnosDisponibles(item, true, callback);
              }

              $scope.getMedicoAutocomplete = function (value) {
                var params = $scope.fBusquedaPlanning;
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
            }
          });
        }
      }

      $scope.cambiarVista = function(){
        $scope.listarHistorial();
      }

      $scope.resumenReserva = function(){
        ventaServices.sValidarCitas($scope.fSessionCI).then(function(rpta){
          if(rpta.flag == 1){
            programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
              $scope.goToUrl('/resumen-cita'); 
            });
          }        
        });     
      }

      $scope.quitarDeLista = function(index, fila){
        //console.log(index, fila);
        $scope.fSessionCI.listaCitas.splice( index, 1 );
        programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
          console.log(rpta);
        });
      }
  })
  .service("historialCitasServices",function($http, $q) {
    return({
      sCargarHistorialCitas:sCargarHistorialCitas,
    });
    function sCargarHistorialCitas(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"HistorialCitas/lista_historial_citas", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }  
  });