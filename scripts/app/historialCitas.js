angular.module('theme.historialCitas', ['theme.core.services'])
  .controller('historialCitasController', function($scope, $theme, $filter
    ,historialCitasServices
    ,sedeServices ){
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
      var datos = {
        search:1,
        nameColumn:'tiene_prog_cita'
      };
      sedeServices.sListarSedesCbo(datos).then(function (rpta) {
        $scope.listaSedes = rpta.datos;
        $scope.fBusqueda.sede = $scope.listaSedes[0];
      });
  })
  .service("historialCitasServices",function($http, $q) {
    return({
    });
  });