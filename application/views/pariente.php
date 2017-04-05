<section class="page-title">
    <div class="container clearfix">
        <div class="row">
            <div class="col-md-6" >
                <h2>Administrar Parientes</h2>
            </div>
            <div class="col-md-6" >
                <ol class="breadcrumb m-n pull-right">
                  <li><a href="#/">Inicio</a></li>
                  <li>Mi Perfil</li>
                  <li class="active">Administrar Parientes</li>
                </ol>
            </div>
        </div>
    </div>
</section> 
<div class="content container"  ng-controller="parienteController" >
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    <div class="col-xs-12 p-n mb-xs" style="margin-top:5px" > 
                      <div class="input-group" style="width:100%;" > 
                         <strong class="control-label mb-n">PARENTESCO: </strong>
                         <select class="form-control input-sm" ng-model="fBusqueda.parentesco"
                           ng-change="listarPlaningMedicos();"
                           ng-options="item.descripcion for item in listaParentescos">
                        </select>                            
                      </div>
                    </div>

                    <div class="col-xs-12 p-n mb-xs" style="margin-top:5px" > 
                      <div class="input-group" style="width:100%;" > 
                         <strong class="control-label mb-n">NOMBRE: </strong>
                         <input type="text" ng-model="fBusqueda.medico" class="form-control input-sm" autocomplete="off"
                           placeholder="Digite un nombre..." 
                                typeahead-loading="loadingLocations" 
                                uib-typeahead="item as item.medico for item in getMedicoBusquedaAutocomplete($viewValue)" 
                                typeahead-min-length="2" 
                                typeahead-on-select="getSelectedMedicoBusqueda($item, $model, $label)"
                                ng-change="fBusqueda.itemMedico = null;" />                            
                      </div>
                    </div>

                    <div class="col-xs-12 p-n mb-xs mt-xs" style="text-align:center;"> 
                      <button type="button" class="btn btn-default btn-sm" ng-click="listarPlaningMedico();" style="width: 100%;"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>

                    <div class="col-xs-12 p-n mb-xs mt-xs" style="text-align:center;margin-top:10px;"> 
                      <button type="button" class="btn btn-page btn-sm" ng-click="btnNuevoPariente();" style="width: 100%;"><i class="fa fa-plus"></i> NUEVO</button>
                    </div>
                </div>

                <div class="col-md-10 col-sm-12">
                    <div class="panel" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                        <div class="panel-body">
                            <div ui-grid="gridOptions" ui-grid-pagination ui-grid-selection ui-grid-resize-columns ui-grid-auto-resize ui-grid-move-columns class="grid table-responsive"></div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>