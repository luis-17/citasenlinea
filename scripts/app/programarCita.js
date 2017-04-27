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

    $scope.initSeleccionarCita=function(){      
      console.log('$scope.familiarSeleccionado', $scope.familiarSeleccionado);
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

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fDataUser = response.datos;
          $scope.fSessionCI = response.datos;
          $scope.fSessionCI.compraFinalizada = false;
          if(!$scope.fSessionCI.nombre_imagen || $scope.fSessionCI.nombre_imagen === ''){
            $scope.fSessionCI.nombre_imagen = 'noimage.jpg';
          }
        }
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

          if($scope.familiarSeleccionado){
            angular.forEach($scope.listaFamiliares, function(value, key) {
              if(value.idusuariowebpariente == $scope.familiarSeleccionado.idusuariowebpariente){
                $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[key];
              }                
            });
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

    $scope.goToHistorial = function(){
      $scope.goToUrl('/historial-citas');
    }

    $scope.goToSelCita = function(){
      $scope.goToUrl('/seleccionar-cita');
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

    $scope.verTurnosDisponibles = function(item, boolExterno, callback){
      if(boolExterno){
        $scope.boolExterno = true;
      } else {
        $scope.boolExterno = false;
      }
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
          $scope.fPlanning.detalle = item;

          $scope.cargarTurnos = function(){
            programarCitaServices.sCargarTurnosDisponibles($scope.fPlanning.detalle).then(function(rpta){
              $scope.fPlanning.turnos=rpta.datos;            
            }); 
          } 
          $scope.cargarTurnos();            

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }

          $scope.checkedCupo = function(cupo){
            $scope.fSeleccion = cupo; 
            cupo.checked=true;
          }

          $scope.btnReservarTurno = function(){ 
            var encontro = false;
            angular.forEach($scope.fSessionCI.listaCitas, function(value, key){              
              if(value.seleccion.iddetalleprogmedico == $scope.fSeleccion.iddetalleprogmedico){
                encontro = true;
              }
            });   

            if(encontro){
              $uibModal.open({ 
                templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
                size: 'sm',
                //backdrop: 'static',
                //keyboard:false,
                scope: $scope,
                controller: function ($scope, $modalInstance) {                 
                  $scope.titleForm = 'Aviso'; 
                  $scope.msj = 'El turno seleccionado ya ha sido escogido para otra cita de su sesión';

                  $scope.btnCancel = function(){
                    $modalInstance.dismiss('btnCancel');
                  }
                }
              });
            }else{
              var datos = {
                busqueda:angular.copy($scope.fBusqueda) ,
                seleccion:angular.copy($scope.fSeleccion)
              }

              $scope.fSessionCI.listaCitas.push(datos);
              programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
                console.log(rpta);
              });
              $scope.btnCancel();
            }
          }

          $scope.btnCambiarTurno = function(){
            $scope.fPlanning.citas.seleccion = $scope.fSeleccion;
            $scope.fDataModal= $scope.fPlanning.citas;
            $scope.fDataModal.oldCita.itemFamiliar.paciente = $scope.fDataModal.oldCita.itemFamiliar.paciente.toUpperCase(); 
            $scope.fDataModal.mensaje = '¿Estas seguro de realizar el cambio?';
            console.log($scope.fDataModal);
            $uibModal.open({ 
              templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_confirmacion',
              size: '',
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = 'Aviso'; 
                $scope.msj = 'El turno seleccionado ya ha sido escogido para otra cita de su sesión';

                $scope.btnClose = function(){
                  $modalInstance.dismiss('btnClose');
                }

                $scope.btnOk = function(){
                  programarCitaServices.sCambiarCita($scope.fPlanning.citas).then(function(rpta){
                    var modal = false;
                    var titulo = '';
                    if(rpta.flag==1){
                      $scope.btnClose();
                      $scope.btnCancel();                  
                      modal = true;
                      titulo = 'Genial!';              
                    }else if(rpta.flag == 0){
                      modal = true;
                      titulo = 'Aviso'; 
                      $scope.cargarTurnos();  
                    }else{
                      alert('Erros inesperado');
                    }

                    if(modal){
                       $scope.mostrarMsj(rpta.flag,titulo,rpta.message, callback);
                    }
                  });
                }
              }
            });            
          }
        
          blockUI.stop();
        }
      });
    }

    $scope.mostrarMsj = function(tipo,titulo, msg, callback){      
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
        size: 'sm',
        //backdrop: 'static',
        //keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = titulo; 
          $scope.msj = msg;

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
            if(tipo==1){              
              callback();
            }
          }
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

    $scope.quitarDeLista = function(index, fila){
      //console.log(index, fila);
      $scope.fSessionCI.listaCitas.splice( index, 1 );
      programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
        console.log(rpta);
      });
    }

    $scope.resumenReserva = function(){
      programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
        $scope.goToUrl('/resumen-cita'); 
      });         
    }

    $scope.initResumenReserva = function(){
      $scope.viewResumenCita = true;
      $scope.viewResumenCompra = false;

      /*  
      $scope.viewResumenCita = false;
      $scope.viewResumenCompra = true; 
      */

      $scope.generarCargo = function(token){
        blockUI.start('Procesando pago... Espere y NO recargue la página');
        var datos = {
          usuario:$scope.fSessionCI,
          token: token
        }

        programarCitaServices.sGenerarVenta(datos).then(function(rpta){          
          var titulo = '';
          var modal = true;
          if(rpta.flag == 1){
            titulo = 'Genial!';
          }else if(rpta.flag == 0){
            titulo = 'Aviso!';
          }else{
            alert('Error inesperado');
            modal = false;
          }
          blockUI.stop();
          if(modal){            
            $uibModal.open({ 
              templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
              size: 'sm',
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = titulo; 
                $scope.msj = rpta.message;

                $scope.btnCancel = function(){
                  $modalInstance.dismiss('btnCancel');
                }

                if(rpta.flag == 1){
                  setTimeout(function() {
                    var callback = function(){
                      $scope.btnCancel();
                    }
                    $scope.goToResumenCompra(callback);
                  }, 1000);
                }
              }
            });
          }
        });
      }

      window.initCulqi = function(value) {
        Culqi.publicKey = 'pk_test_5waw7MlH2GomYjCx'; //cambiar por servicio config
        Culqi.settings({
            title: 'Villa Salud',
            currency: 'PEN',
            description: 'Pago de Citas en linea',
            amount: value,            
        });
        
        window.culqi = function(){
          console.log('entro por aqui');
          if(Culqi.token) { // ¡Token creado exitosamente!
            // Get the token ID:
            var token = Culqi.token;
            $scope.generarCargo(token);
          }else{ // ¡Hubo algún problema!
            // Mostramos JSON de objeto error en consola
            console.log(Culqi.error);
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
          }
        }        
      }

      $scope.pagar = function(){
        console.log($scope.fSessionCI);
        Culqi.open();
      }

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
          programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(response){
            $scope.totales = {};
            $scope.totales.total_productos = response.datos.totales.total_productos;
            $scope.totales.total_servicio = response.datos.totales.total_servicio;
            $scope.totales.total_pago = response.datos.totales.total_pago;
            $scope.totales.total_pago_culqi = response.datos.totales.total_pago_culqi;
            window.initCulqi($scope.totales.total_pago_culqi);  
            if($scope.fSessionCI.listaCitas.length < 1){
              $scope.goToUrl('/seleccionar-cita');
            }       

            $scope.listaCitas = $scope.fSessionCI.listaCitas;
          });          
        }           
      });    
    }

    $scope.goToResumenCompra = function(callback){
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos; 
          $scope.getNotificacionesEventos();         
        } 

        $scope.viewResumenCita = false;
        $scope.viewResumenCompra = true; 
        callback();           
      });
    }

    $scope.descargaComprobante = function(cita){
      console.log(cita);
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
      sGenerarVenta:sGenerarVenta,
      sVerificaEstadoCita:sVerificaEstadoCita,
      sCambiarCita:sCambiarCita,
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
    function sGenerarVenta(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/generar_venta", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sVerificaEstadoCita(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/verifica_estado_cita", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sCambiarCita(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cambiar_cita", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
