<div class="msj modal-close " >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="msj modal-header reprogramacion" style="padding-top: 50px;">
	<div class="filtros btn-group-btn pl-n ml-n">
		<ul class="demo-btns">			
			<li class="" >	<label class="control-label">Cita para:</label>		</li>
			<li class="" style="padding: 0 0 0 20px;">
				<select class="form-control" disabled ng-model="fBusquedaRep.itemFamiliar"
			ng-change="" 
			ng-options="item.descripcion for item in listaFamiliares">
			</select>
			</li>
			<li class="" >
				<li class="" >	<label class="control-label">en:</label>		</li>
			</li>
			<li class="" >
				<select class="form-control " disabled ng-model="fBusquedaRep.itemSede"
					ng-change="listarEspecialidad();" 
					ng-options="item.descripcion for item in listaSedes">
				</select>
			</li>
			<li class="" >
				<select class="form-control " disabled ng-model="fBusquedaRep.itemEspecialidad"
					ng-change="" 
					ng-options="item.descripcion for item in listaEspecialidadRep">
				</select>
			</li>
			<li class="" >	    		 
				<label class="control-label">Fecha:</label>	        
			</li>
			<li class="" >	    		 
				<input type="text" class="form-control mask" ng-model="fBusquedaPlanning.desde" placeholder="Desde" data-inputmask="'alias': 'dd-mm-yyyy'" style="width:50px !important;" /> 
			</li>
			<li class="" >
				<input type="text" ng-model="fBusquedaPlanning.medico" class="form-control " autocomplete="off"
					placeholder="Digite el Médico..." 
					typeahead-loading="loadingLocations" 
					uib-typeahead="item as item.medico for item in getMedicoAutocomplete($viewValue)" 
					typeahead-min-length="2" 
					typeahead-on-select="getSelectedMedico($item, $model, $label)"
					ng-change="fBusquedaPlanning.itemMedico = null;" />
			</li>
			
 	</ul>
	</div>
</div>
<div class="msj modal-body row" style="font-size: initial;">
	<form class="row">				
		<div class="col-xs-12 col-sm-12 col-md-12">
          	<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1" ng-if="!fPlanning.mostrar && fPlanning.mostraralerta" >
	          <div class="alert alert-warning" >
	            No hay turnos diponibles con las opciones seleccionadas, intenta con otros parámetros... 
	          </div>
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
	                      <span class="favorito animation" uib-tooltip="{{fBusqueda.medico}}" tooltip-placement="top" ng-if="item.medico_favorito">
	                      	<i class="fa fa-star" style="font-size: 15px;"></i>
	                      </span>
	                      <a href="" class="label label-info" ng-click="viewTurnos(item); $event.stopPropagation();">{{item.dato}} </a>
	                    </div>
	                  </div>
	                </div>       
	              </div> 
	            </div>
	            <div class="clearfix"></div>
          	</div>           
        </div>
	</form>
</div>


