angular.module('theme.resultadolaboratorio', ['theme.core.services'])
  .controller('resultadolaboratorioController', ['$scope', '$sce', '$filter','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'resultadolaboratorioServices', 'ModalReporteFactory' ,
    'rootServices',
    function($scope, $sce, $filter, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      resultadolaboratorioServices, ModalReporteFactory ,
      rootServices
    ){ 
    'use strict'; 
      $scope.orden = {}; 
      $scope.fData = {};
      $scope.siexiste = false ;
      $scope.vistabla1 = true ;
      $scope.vistabla2 = false ;
      $scope.vistabla3 = false ;            

      $scope.datosGrid = {
        //paginate : paginationOptions,
        idcliente : $scope.fSessionCI.idcliente
      };

      resultadolaboratorioServices.sCargaResultadoUsuario($scope.datosGrid).then(function (rpta) {
          $('#grid').grid({
              dataSource: rpta.datos,
              iconsLibrary: 'fontawesome',
              fontSize: '13px',
              selectionType: 'single',
              selectionMethod: 'checkbox' , 
              notFoundText: 'No records found custom message',            
              columns: [ 
                  { field: 'id' , title : 'ID' , hidden : true}, 
                  { field: 'orden_lab' , title : 'ORDEN LAB' , hidden : true}, 
                  { field: 'idsedeempresaadmin' , title : 'Empresa Admin' , hidden : true},                    
                  { field: 'orden_venta' , title : 'Numero de Orden' , headerCssClass: 'gridheader'},
                  { field: 'idhistoria' , title : 'Historia Clinica' , headerCssClass: 'gridheader'} ,
                  { field: 'fecha_recepcion' , title : 'Fecha de Recepción' , headerCssClass: 'gridheader'}, 
                  { field: 'tipomuestra' , title : 'Tipo de Muestra' , headerCssClass: 'gridheader'}, 
                  { field: 'sede' , title : 'Sede' , headerCssClass: 'gridheader' } ,
                  { title: '', field: 'ver', width: 30, type: 'icon', icon: 'fa fa-search', tooltip: 'Mostrar Resultados.' , events: { 'click': function (e) { $scope.btnVerResultados(e.data.record.orden_lab , e.data.record.idsedeempresaadmin);}} , headerCssClass: 'gridheader' },
              ]
          });
      });

      $scope.btnVerResultados = function($ord_lab,$idsea){
        $scope.gridDetail = null ;
        $scope.objects = [];
        $scope.vistabla1 = true ;
        $scope.vistabla2 = true ;
        $scope.vistabla3 = false ; 


        $scope.orden['orden_lab'] = $ord_lab ;
        $scope.orden['idsedeempresaadmin'] = $idsea ;

        if($scope.siexiste){
          $('#gridDetail').grid('destroy', true, true);
        }

        resultadolaboratorioServices.sListarPacienteConResultados($scope.orden).then(function (rpta) { 

            var createFunct = function() {
              $scope.gridDetail = $('#gridDetail').grid({
                  dataSource: rpta.arrAnalisis,
                  iconsLibrary: 'fontawesome',
                  fontSize: '13px',
                  selectionType: 'multiple',
                  selectionMethod: 'checkbox' , 
                  notFoundText: 'No records found custom message',                           
                  columns: [ 
                      { field: 'idanalisis' , title : 'ID' , hidden : true },
                      { field: 'idseccion' , title : 'IDSEC' , hidden : true },  
                      { field: 'seccion' , title : 'Seccion' ,headerCssClass: 'gridheader' }, 
                      { field: 'descripcion_anal' , title : 'Analisis' ,headerCssClass: 'gridheader' }, 
                      { field: 'fecha_resultado' , title : 'Fecha de Resultado' , headerCssClass: 'gridheader'}, 
                      { field: 'estado' , title : 'Estado' , headerCssClass: 'gridheader'} ,
                      { title: '', field: 'ver', width: 30, type: 'icon', icon: 'fa fa-eye', tooltip: 'Mostrar Resultados.' , events: { 'click': function (e) { alert('name=' + e.data.record.id);}} , headerCssClass: 'gridheader' },
                      { title: '', field: 'imprimir', width: 30, type: 'icon', icon: 'fa fa-print', tooltip: 'Imprimir Resultados.' , events: { 'click': function (e) { $scope.btnImprimirSel();}} , headerCssClass: 'gridheader' }
                  ]
              });
            };
            createFunct();

          if(rpta.flag == 1){ 
            var pTitle = 'OK!';
            var pType = 'success';
            $scope.vistabla1 = false ;
            $scope.vistabla2 = false ;
            $scope.vistabla3 = true ;            
            $scope.pacEncontrado = true;
            $scope.siexiste = true ;
            $scope.fData = rpta.datos;
            $scope.fDataArrPrincipal = rpta.arrSecciones;
            //$scope.gridOptions.data = rpta.arrAnalisis;

            // console.log(rpta.arrAnalisis);
            // $('#p00').focus();
          }else if(rpta.flag == 0){
            $scope.pacEncontrado = false;
            $scope.fData = {};
            var pTitle = 'Error!';
            var pType = 'danger';
          }else{
            alert('Se ha producido un problema. Contacte con el Area de Sistemas');
          }
          pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3500 });
        });        
      }

      $scope.volver = function(){
        $scope.vistabla1 = true ;
        $scope.vistabla2 = false ;
        $scope.vistabla3 = false;  
        
        if($scope.siexiste){
          $('#gridDetail').grid('destroy', true, true);
        }              
      }

    
      $scope.btnImprimirSel = function (){
        var objSel = [];
        var nume = 0 ;

        angular.forEach($scope.fDataArrPrincipal,function(valueAP, keyAP){ 
          angular.forEach(valueAP.analisis,function(valueAnal, keyAnal){ 
            $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].seleccionado = false;
            $scope.fDataArrPrincipal[keyAP].seleccionado = false;
          });
        });

        var selections = $scope.gridDetail.getSelections();
        var i ;

        $.each(selections, function() {
            i = this ;
            objSel[i-1] = $scope.gridDetail.get(this);
        });

          console.log("selection: ",objSel.length);
          console.log("data principal",$scope.fDataArrPrincipal);
          if( objSel.length >= 1 ){
            angular.forEach(objSel , function(value,key){
              angular.forEach($scope.fDataArrPrincipal,function(valueAP, keyAP){ 
                angular.forEach(valueAP.analisis,function(valueAnal, keyAnal){ 
                  if( valueAP.idseccion == value.idseccion && valueAnal.idanalisis == value.idanalisis ){
                    $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].seleccionado = true;
                    $scope.fDataArrPrincipal[keyAP].seleccionado = true;
                  }
                });
              });            
            });
            $scope.fData.arrSecciones = $scope.fDataArrPrincipal; 
            // $scope.fBusqueda.titulo = $scope.selectedReport.name;
            // $scope.fBusqueda.tituloAbv = $scope.selectedReport.id;
            var arrParams = {
              titulo: 'RESULTADO DE LABORATORIO',
              datos:{
                resultado: $scope.fData,
                salida: 'pdf',
                tituloAbv: 'LAB-RL',
                titulo: 'RESULTADO DE LABORATORIO'
              },
              metodo: 'php'
            } 
            arrParams.url = angular.patchURLCI+'CentralReportesMPDF/report_resultado_laboratorio', 
            ModalReporteFactory.getPopupReporte(arrParams); 
            console.log("arrParams :",arrParams);
            return false; 
          }else{
            pinesNotifications.notify({ title: 'Advertencia', text: 'No seleccionó ninguna orden.', type: 'warning', delay: 3500 });
          }
      }    


  }])
  .service("resultadolaboratorioServices",function($http, $q) {
    return({
      sCargaResultadoUsuario :sCargaResultadoUsuario ,
      sListarPacienteConResultados : sListarPacienteConResultados
    });
    function sCargaResultadoUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Resultadolaboratorio/carga_resultados_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sListarPacienteConResultados(pDatos) { 
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Resultadolaboratorio/listarPacientesConResultados", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    

  }); 