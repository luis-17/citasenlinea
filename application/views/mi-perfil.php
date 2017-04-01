<section class="page-title">
    <div class="container clearfix">
        <div class="row">
            <div class="col-md-6" >
                <h2>Actualizar mis datos</h2>
            </div>
            <div class="col-md-6" >
                <ol class="breadcrumb m-n pull-right">
                  <li><a href="#/">Inicio</a></li>
                  <li>Mi Perfil</li>
                  <li class="active">Actualizar mis datos</li>
                </ol>
            </div>
        </div>
    </div>
</section> 
<div class="content container"  ng-controller="usuarioController" ng-init="init();">
    <div data-widget-group="group1" class="ui-sortable">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel panel-profile" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                      <div class="panel-body">
                        <img  masked-image="" class="img-circle" ng-src="{{ dirImages + 'dinamic/usuario/' + fSessionCI.nombre_foto }}" alt=" {{ fSessionCI.username }} " />                
                      </div>
                      <p>Editar Imagen</p>
                    </div>

                    <div class="panel panel-profile score-puntos" >
                        
                    </div>
                    <!-- <div class="list-group list-group-alternate mb-n nav nav-tabs">
                        <a href="" ng-click="selectedTab='0'" ng-class="{active: selectedTab=='0'}" class="list-group-item active"><i class="ti ti-user"></i> About <span class="badge badge-primary">80%</span></a>
                        <a href="" ng-click="selectedTab='1'" ng-class="{active: selectedTab=='1'}" class="list-group-item"><i class="ti ti-time"></i> Timeline</a>
                        <a href="" ng-click="selectedTab='2'" ng-class="{active: selectedTab=='2'}" class="list-group-item"><i class="ti ti-view-list-alt"></i> Projects</a>
                        <a href="" ng-click="selectedTab='3'" ng-class="{active: selectedTab=='3'}" class="list-group-item"><i class="ti ti-view-grid"></i> Photos</a>
                        <a href="" ng-click="selectedTab='4'" ng-class="{active: selectedTab=='4'}" class="list-group-item"><i class="ti ti-pencil"></i> Edit</a>
                    </div> -->
                </div><!-- col-sm-3 -->
                <div class="col-sm-9">
                    <div class="tab-content">
                        <div class="tab-pane active" ng-class="{active: selectedTab=='0'}">
                            <div class="panel panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <h4>Información Personal</h4>
                                            <div class="col-sm-12">
                                                <div class=" ">
                                                    
                                                        <div class="form-group col-sm-6">
                                                            <label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
                                                            <input type="text" class="form-control input-sm" ng-model="fData.num_documento" required disabled tabindex="1" />
                                                        </div>
                                                        

                                                        <div class="form-group col-sm-6">
                                                            <label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
                                                            <input type="text" class="form-control input-sm" ng-model="fData.nombres" placeholder="Registre su nombre" required tabindex="2" />
                                                        </div>
                                                  
                                                        <div class="form-group col-sm-6">
                                                            <label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
                                                            <input type="text" class="form-control input-sm" ng-model="fData.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="3" /> 
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
                                                            <input type="text" class="form-control input-sm" ng-model="fData.apellido_materno" placeholder="Registre su apellido materno" required tabindex="4" /> 
                                                        </div>          
                                                    
                                                        <div class="form-group col-sm-6" >
                                                            <label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
                                                            <input type="email" class="form-control input-sm" ng-model="fData.email" placeholder="Registre su e-mail" required tabindex="5" />
                                                        </div>

                                                        <div class="form-group col-sm-3" >
                                                            <label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
                                                            <select class="form-control input-sm" ng-model="fData.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="6" required > </select>
                                                        </div>

                                                        <div class="form-group col-sm-3" >
                                                            <label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
                                                            <input type="text" class="form-control input-sm mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fData.fecha_nacimiento" required tabindex="7"/> 
                                                        </div>
                                                    
                                                        <div class="form-group col-sm-3">
                                                            <label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
                                                            <input type="tel" class="form-control input-sm" ng-model="fData.celular" placeholder="Registre su celular" ng-minlength="9" required tabindex="8" />
                                                        </div>
                                                        <div class="form-group col-sm-3">
                                                            <label class="control-label mb-xs">Teléfono Casa  </label>
                                                            <input type="tel" class="form-control input-sm" ng-model="fData.telefono" placeholder="Registre su teléfono" ng-minlength="6" tabindex="9" />
                                                        </div>                  
                                                    
                                                </div> 
                                            </div>

                                            <h4>Información de cuenta</h4>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="form-password" class="col-sm-2 control-label">Password</label>
                                                    <div class="col-sm-8 tabular-border">
                                                        <input type="password" class="form-control" id="form-password" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="form-confirmpass" class="col-sm-2 control-label">Confrim Password</label>
                                                    <div class="col-sm-8 tabular-border">
                                                        <input type="password" class="form-control" id="form-confirmpass" placeholder="Password">
                                                    </div>
                                                </div>
                                                                                           
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-sm-8 col-sm-offset-2">
                                            <button class="btn-primary btn">Save</button>
                                            <button class="btn-default btn">Reset</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!-- .tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>