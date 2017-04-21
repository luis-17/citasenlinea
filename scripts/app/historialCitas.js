angular.module('theme.historialCitas', ['theme.core.services'])
  .controller('historialCitasController', function($scope, $theme, $filter,
    historialCitasServices,
    sedeServices,
    especialidadServices,
    parienteServices,
    rootServices ){
      'use strict';
      shortcut.remove("F2"); 
      $scope.modulo = 'historialCitas'; 
      $scope.pageTittle = 'Inicio';
      $scope.listaMeses = [
        { 'id': 1, 'mes': 'Enero' },
        { 'id': 2, 'mes': 'Febrero' },
        { 'id': 3, 'mes': 'Marzo' },
        { 'id': 4, 'mes': 'Abril' },
        { 'id': 5, 'mes': 'Mayo' },
        { 'id': 6, 'mes': 'Junio' },
        { 'id': 7, 'mes': 'Julio' },
        { 'id': 8, 'mes': 'Agosto' },
        { 'id': 9, 'mes': 'Septiembre' },
        { 'id': 10, 'mes': 'Octubre' },
        { 'id': 11, 'mes': 'Noviembre' },
        { 'id': 12, 'mes': 'Diciembre' }
      ];
      var mes_actual = $filter('date')(new Date(),'M');

      $scope.fBusqueda = {};
      $scope.fBusqueda.tipoCita = 'pendientes';
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
          $scope.listaFamiliares.splice(0,0,{ idusuariowebpariente:0, descripcion: $scope.fSessionCI.nombres + ' (titular)'});
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
          //console.log(rpta);
          $scope.listaDeCitas = rpta.datos;
        });
      }
      $scope.listarHistorial();

      $scope.reprogramarCita = function(cita){
        console.log(cita);

      }

      $scope.cambiarVista = function(){
        //console.log($scope.fBusqueda.tipoCita);
        $scope.listarHistorial();
      }

      $scope.resumenReserva = function(){
        $scope.goToUrl('/resumen-cita');          
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