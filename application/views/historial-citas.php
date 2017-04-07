<div class="content container" ng-controller="historialCitasController">       
      <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
          <div class="col-md-12">
              <div class="row">
                  <div class="col-md-2 col-sm-12">
                      <div class="col-xs-12 p-n mb-xs" style="margin-top:5px" > 
                        <div class="input-group" style="width:100%;" > 
                           <strong class="control-label mb-n">SEDE: </strong>
                           <select class="form-control input-sm" ng-model="fBusqueda.sede"
                             ng-change="listarPlaningMedicos();"
                             ng-options="item.descripcion for item in listaSedes"                               >
                          </select>                            
                        </div>
                      </div>

                      <div class="col-xs-12 p-n mb-xs" style="margin-top:5px" > 
                        <div class="input-group" style="width:100%;" > 
                           <strong class="control-label mb-n">MÉDICO: </strong>
                           <input type="text" ng-model="fBusqueda.medico" class="form-control input-sm" autocomplete="off"
                             placeholder="Digite el Médico..." 
                                  typeahead-loading="loadingLocations" 
                                  uib-typeahead="item as item.medico for item in getMedicoBusquedaAutocomplete($viewValue)" 
                                  typeahead-min-length="2" 
                                  typeahead-on-select="getSelectedMedicoBusqueda($item, $model, $label)"
                                  ng-change="fBusqueda.itemMedico = null;" />                            
                        </div>
                      </div>

                      <div class="col-xs-12 p-n mb-xs" style="margin-top:5px " > 
                        <div class="input-group" style="width:100%;" > 
                           <strong class="control-label mb-n">ESPECIALIDAD: </strong>
                           <input type="text" ng-model="fBusqueda.especialidad" class="form-control input-sm" autocomplete="off"
                             placeholder="Digite Especialidad..." 
                                  typeahead-loading="loadingLocations" 
                                  uib-typeahead="item as item.descripcion for item in getEspecialidadBusquedaAutocomplete($viewValue)" 
                                  typeahead-min-length="2" 
                                  typeahead-on-select="getSelectedEspecialidadBusqueda($item, $model, $label)"
                                  ng-change="fBusqueda.itemEspecialidad = null" />                            
                        </div>
                      </div>

                      <div class="col-xs-12 p-n mb-xs" style="margin-top:5px">
                          <strong class="control-label mb-n">FECHA: </strong>
                          <input tabindex="110" type="text" placeholder="Fecha" class="form-control input-sm mask" ng-model="fBusqueda.fecha" style="width: 100%;" data-inputmask="'alias': 'dd-mm-yyyy'" /> 
                      </div>

                      <div class="col-xs-12 p-n mb-xs mt-xs" style="text-align:center;"> 
                        <button type="button" class="btn btn-page btn-sm" ng-click="listarPlaningMedico();" style="width: 100%;"><i class="fa fa-search"></i> BUSCAR</button>
                      </div>
                  </div>
                  <div class="col-md-10 col-sm-12">
                      
                  </div>
              </div>
          </div>
      </div>

</div>