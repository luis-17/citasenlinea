angular.module('theme.pariente', ['theme.core.services'])
  .controller('parienteController', ['$scope', '$sce', '$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI',
    'parienteServices',
    'parentescoServices',
    function($scope, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
     parienteServices,
     parentescoServices
    ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'pariente';
    $scope.fBusqueda = {};
    parentescoServices.sListarParentescoCbo().then(function(rpta){
      $scope.listaParentescos = rpta.datos;
      $scope.listaParentescos.splice(0,0,{ id : 0, idparentesco:0, descripcion:'--VER TODOS --'});
      $scope.fBusqueda.parentesco = $scope.listaParentescos[0];
    });

    $scope.listaSexos = [
      {id:'-', descripcion:'SELECCIONE SEXO'},
      {id:'F', descripcion:'FEMENINO'},
      {id:'M', descripcion:'MASCULINO'}
    ];

    var paginationOptions = {
      pageNumber: 1,
      firstRow: 0,
      pageSize: 10,
      sort: uiGridConstants.ASC,
      sortName: null,
      search: null
    };

    $scope.mySelectionGrid = [];

    $scope.gridOptions = {
      paginationPageSizes: [10, 50, 100, 500, 1000],
      paginationPageSize: 10,
      useExternalPagination: true,
      useExternalSorting: true,
      enableGridMenu: true,
      enableRowSelection: true,
      enableSelectAll: true,
      enableFiltering: true,
      enableFullRowSelection: true,
      multiSelect: true,
      columnDefs: [
        { field: 'idusuariowebpariente', name: 'idusuariowebpariente', displayName: 'ID', width: '8%',  sort: { direction: uiGridConstants.ASC} },
        { field: 'pariente', name: 'pariente', displayName: 'Pariente', },        
        { field: 'parentesco', name: 'parentesco', displayName: 'Parentesco', width: '20%', }, 
        { field: 'sexo', name: 'sexo', displayName: 'Sexo', width: '12%', },       
      ],
      onRegisterApi: function(gridApi) {
        $scope.gridApi = gridApi;
        gridApi.selection.on.rowSelectionChanged($scope,function(row){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });

        $scope.gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
          if (sortColumns.length == 0) {
            paginationOptions.sort = null;
            paginationOptions.sortName = null;
          } else {
            paginationOptions.sort = sortColumns[0].sort.direction;
            paginationOptions.sortName = sortColumns[0].name;
          }
          $scope.getPaginationServerSide();
        });
        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          paginationOptions.firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
          $scope.getPaginationServerSide();
        });
        $scope.gridApi.core.on.filterChanged( $scope, function(grid, searchColumns) {
          var grid = this.grid;
          paginationOptions.search = true;
          // console.log(grid.columns);
          // console.log(grid.columns[1].filters[0].term);
          paginationOptions.searchColumn = {
            'idusuariowebpariente' : grid.columns[1].filters[0].term,
            "concat_ws(' ',  c.nombres, c.apellido_paterno, c.apellido_materno)" : grid.columns[2].filters[0].term,
            'cp.descripcion' : grid.columns[3].filters[0].term,
            'c.sexo' : grid.columns[4].filters[0].term,
          }
          $scope.getPaginationServerSide();
        });
      }
    };

    paginationOptions.sortName = $scope.gridOptions.columnDefs[0].name;
    $scope.getPaginationServerSide = function(){
      $scope.datosGrid = {
        paginate : paginationOptions
      };
      parienteServices.sListarParientes($scope.datosGrid).then(function (rpta) {
        $scope.gridOptions.totalItems = rpta.paginate.totalRows;
        $scope.gridOptions.data = rpta.datos;
      });
      $scope.mySelectionGrid = [];
    };
    $scope.getPaginationServerSide();

    $scope.btnNuevoPariente = function(){
      $scope.fData = {}; 
      $scope.fData.sexo = '-'; 
      $scope.regListaParentescos = angular.copy($scope.listaParentescos);
      $scope.regListaParentescos[0].descripcion = 'SELECCIONE PARENTESCO';
      $scope.fData.parentesco = $scope.regListaParentescos[0];

      blockUI.start('Abriendo formulario...');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Pariente/ver_popup_formulario',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Registro de Parientes';     

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
            parienteServices.sVerificarParientePorDocumento($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              if( rpta.flag == 2 ){ //Cliente registrado en Sistema Hospitalario
                $scope.fData = rpta.usuario;
                $scope.fData.parentesco = $scope.regListaParentescos[0];
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
                $scope.fData.parentesco = $scope.regListaParentescos[0];
              }
              $scope.fAlert.flag = rpta.flag;
            });
          }

          $scope.btnRegistrarPariente = function (){
            parienteServices.sRegistrarPariente($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              if(rpta.flag == 0){
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error';
                $scope.fAlert.icon = 'fa fa-exclamation';
              }else if(rpta.flag == 1){
                $scope.fData = {};
                $scope.fData.sexo = '-';
                $scope.fAlert.type= 'success';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.icon= 'fa fa-smile-o';
                $scope.fAlert.strStrong = 'Genial! ';
              }
              $scope.fAlert.flag = rpta.flag;
            });
          }   
        
          blockUI.stop();
        }
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
  .service("parienteServices",function($http, $q) {
    return({
        sListarParientes: sListarParientes,  
        sVerificarParientePorDocumento: sVerificarParientePorDocumento,
        sRegistrarPariente:sRegistrarPariente,     
    });
    function sListarParientes(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/lista_parientes", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sVerificarParientePorDocumento(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/verificar_pariente_por_documento", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sRegistrarPariente(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/registrar_pariente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
