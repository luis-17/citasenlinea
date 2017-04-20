<div class="content container" ng-controller="programarCitaController" ng-init="initSeleccionarCita();" > 
	<div class="filtros btn-group-btn pl-n ml-n">
		<ul class="m-xs demo-btns">
			<li class="" >
        Programar cita para:
      </li>
      <li class="" style="padding: 0 0 0 20px;">
        <select class="form-control " ng-model="fBusqueda.itemFamiliar"
            ng-change="" 
            ng-options="item.descripcion for item in listaFamiliares">
          </select>
          
      </li>
      <li class="" style="padding: 0 0;">
        <button type="button" class="btn btn-page btn-sm" ng-click="btnAgregarNuevoPariente();"><i class="fa fa-plus"></i></button>
      </li>
      <li class="" >
        en:
      </li>
			<li class="" >
				<select class="form-control " ng-model="fBusqueda.itemSede"
					ng-change="listarEspecialidad();" 
					ng-options="item.descripcion for item in listaSedes">
				</select>
			</li>
			<li class="" >
				<select class="form-control " ng-model="fBusqueda.itemEspecialidad"
					ng-change="" 
					ng-options="item.descripcion for item in listaEspecialidad">
				</select>
			</li>
			<li class="" >
				<input type="text" ng-model="fBusqueda.medico" class="form-control " autocomplete="off"
                             placeholder="Digite el Médico..." 
                                  typeahead-loading="loadingLocations" 
                                  uib-typeahead="item as item.medico for item in getMedicoAutocomplete($viewValue)" 
                                  typeahead-min-length="2" 
                                  typeahead-on-select="getSelectedMedico($item, $model, $label)"
                                  ng-change="fBusqueda.itemMedico = null;" />
			</li>

			<li class="">
				<button class="btn btn-page btn-sm" ng-click="cargarPlanning(); " ><i class="ti ti-search"></i></button>
			</li>
		</ul>
	</div>
	<div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">		
		<div class="col-sm-12 col-md-12 col-xs-12" style="margin-top: 100px;">
			<div class="row">
				<div class="col-sm-12 col-md-2 col-xs-12">
          <div class="row">
  					<div class="text-guia">
  						<p class="saludo">¡Hola {{fSessionCI.nombres}}!</p>
  						<p class="instruccion">Programar tu cita es muy sencillo... Selecciona una fecha, escoge la sede, la especialidad y un médico, si tienes algún favorito!</p>
  					</div>
            <div class="col-sm-12 col-md-12 col-xs-12 mb-md p-n leyenda"> 
              Identifica tu médico con el icono: <span class="favorito animation"><i class="fa fa-star"></i></span>                
            </div>
            <div class="col-xs-12 p-n m-n grid-fecha" style="">
              <label class="control-label mb-xs">Fecha seleccionada: </label>
              <input tabindex="110" type="text" placeholder="dd-mm-yyyy" ng-model="fBusqueda.desde" 
                    class="form-control mask mb-sm input-fecha" data-inputmask="'alias': 'dd-mm-yyyy'" disabled /> 
              <uib-datepicker style="width: 100%;" class="full-width" ng-model='fBusqueda.fecha' select-range='false' 
                ng-click="" ng-change="cambiarFechas();"  > 
              </uib-datepicker>
            </div>
            
          </div>
				</div>
				<div class="col-sm-12 col-md-8 pl-n" >
          <div class="alert alert-warning" ng-if="!fPlanning.mostrar && fPlanning.mostraralerta">
            no hay turnos diponibles con las opciones seleccionadas, intenta con otros parámetros... 
          </div>
          <div class="planning" ng-class="{visible : fPlanning.mostrar}" ng-if="fPlanning.mostrar">
            <div class="desc-header" style="width: 121px; ">
              H./FECHAS
            </div>
            <div class="header">                
              <div ng-repeat="fecha in fPlanning.fechas" class="{{fecha.class}}">
                <div class="cell-fecha">{{fecha.dato}}</div>
              </div>                    
            </div>
            <div class="block-visible-planning">
              <div class="sidebar">              
                <div ng-repeat="hora in fPlanning.horas" class="{{hora.class}}">
                  <div class="cell-hora">{{hora.dato}}</div>
                </div>                 
              </div>

              <div class="body" scroller >                              
                <div ng-repeat="column in fPlanning.grid" class="column">
                  <div ng-repeat="item in column" class="{{item.class}}" ng-if="!item.unset" style="height:{{30*item.rowspan}}px;" >
                    <div class="content-cell-column" >
                      <span class="favorito animation" uib-tooltip="{{fBusqueda.medico}}" tooltip-placement="top" ng-if="item.medico_favorito"><i class="fa fa-star"></i></span>
                      <a href="" class="label label-info" ng-click="verTurnosDisponibles(item); $event.stopPropagation();">{{item.dato}} </a>
                    </div>
                  </div>
                </div>       
              </div> 
            </div>
            <div class="clearfix"></div>
          </div>           
        </div>

        <div class="col-sm-12 col-md-2 pl-n">
          <div class="citas scroll-pane" style="">
            <ul class="list-citas">
                <li ng-repeat="(index,fila) in fSessionCI.listaCitas" class="item-list-citas notification-{{fila.clase}}" >
                    <div class="cita" ng-click="" style="">
                      <span class="eliminar" ng-click="quitarDeLista(index,fila);"><i class="fa fa-times" style="color: #ce1d19;"></i></span>  
                      <div><i class="fa fa-stethoscope" style="color: #36c0d1;"></i>  Cita para:    <span class="cita-familiar">{{fila.busqueda.itemFamiliar.descripcion}}</span></div>
                      <div><i class="fa fa-hospital-o"  style="color: #ce1d19;"></i>  Sede:         <span class="cita-sede">{{fila.busqueda.itemSede.descripcion}}</span></div>
                      <div><i class="ti ti-slice"       style="color: #ffc107;"></i>  Especialidad: <span class="cita-esp">{{fila.busqueda.itemEspecialidad.descripcion}}</span></div>
                      <div><i class="fa fa-user-md"     style="color: #191970;"></i>  Médico:       <span class="cita-medico">{{fila.seleccion.medico}}</span></div>
                      <div><i class="fa fa-clock-o"     style="color: #929191;"></i>  Turno:        <span class="cita-turno">{{fila.seleccion.fecha_programada}} {{fila.seleccion.hora_formato}}</span></div>
                    </div>                            
                </li>
                <li class="media" ng-show="fSessionCI.listaCitas.length < 1">
                    <div class="sin-citas"> Comienza a registrar tus citas... </div>
                </li>
            </ul>
            <div class="boton-finalizar" ng-show="fSessionCI.listaCitas.length > 0">
              <a class="" ng-click="resumenReserva();" style="animation: pulse 2s ease infinite;">FINALIZAR  <i class="fa fa-angle-right"></i>
              </a>
            </div>            
          </div>
        </div>
			</div>
		</div>
	</div>
</div>