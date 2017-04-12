angular.module('theme.inicio', ['theme.core.services'])
  .controller('inicioController', function($scope, $theme, $filter
    ,inicioServices
    ,sedeServices ){
      'use strict';
      shortcut.remove("F2"); 
      $scope.modulo = 'inicio'; 
      $scope.arrays = {};
      $scope.fDataFiltro = {};
      $scope.fBusqueda = {};
      $scope.arrays.listaAvisos = [];
      $scope.arrays.listaCumpleaneros = [];
      $scope.arrays.listaTelefonica = [];
      $scope.arrays.listaDocumentosInterno = [];

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
      
      console.log('$scope.fSessionCI',$scope.fSessionCI);

      $scope.goToPerfil = function(){
        $scope.goToUrl('/mi-perfil');
      }
      
  })
  .service("inicioServices",function($http, $q) {
    return({
    });
  });