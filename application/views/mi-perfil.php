<div class="content container"  ng-controller="usuarioController" ng-init="init();" >
    <div data-widget-group="group1" class="ui-sortable">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-2 col-sm-12 pl-n pr-n">
                    <div class="panel-profile" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                      <div class="panel-body">
                        <img  masked-image="" class="img-circle" ng-src="{{ dirImages + 'dinamic/usuario/' + fSessionCI.nombre_imagen }}" alt=" {{ fSessionCI.username }} " /> 
                        <div class="col-sm-12">
                            <a href style="color:#ce1d19;" ng-click="btnCambiarMiFotoPerfil(fDataUser,fSessionCI);">
                                <i class="ti ti-camera"></i> Subir foto
                            </a>               
                        </div>                      
                      </div>                      
                    </div>
                    <div class="list-group list-group-alternate mb-n nav nav-tabs">
                        <a href="" ng-click="selectedTab='0'" ng-class="{active: selectedTab=='0'}" class="list-group-item "><i class="ti ti-pencil"></i> EDITAR</a>
                        <a href="" ng-click="selectedTab='1'; refreshListaParientes();" ng-class="{active: selectedTab=='1'}" class="list-group-item "><i class="fa fa-users"></i> FAMILIARES</a>
                        <a href="" ng-click="selectedTab='2'" ng-class="{active: selectedTab=='2'}" class="list-group-item"><i class="ti ti-check-box"></i> PERFIL CLÍNICO</a>
                    </div>
                    <div class="panel-profile score-puntos" >                        
                    </div>
                </div><!-- col-sm-3 -->
                <div class="col-md-10 col-sm-12" style="padding-bottom:20px;">
                    <div class="tab-content">
                        <div class="tab-pane " ng-class="{active: selectedTab=='0'}">
                            <div class=" panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                                <div class="panel-body">
                                    <div class="col-sm-7">
                                        <div class="row"> 
                                            <div class="tab-heading col-sm-7">
                                                <span class="icon"><i class="ti ti-pencil"></i></span> 
                                                <div>
                                                    <span class="title">Editar</span> 
                                                    <p class="descripcion">Queremos saber más de ti.</p>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">                                                                                           
                                                <div class="row"> 
                                                    <alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                                                        <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                                                    </alert>                                                   
                                                    <div class="form-group col-sm-6">
                                                        <label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
                                                        <input type="text" class="form-control " ng-model="fDataUser.num_documento" required disabled tabindex="1" />
                                                    </div>                                                       

                                                    <div class="form-group col-sm-6">
                                                        <label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
                                                        <input type="text" class="form-control " ng-model="fDataUser.nombres" placeholder="Registre su nombre" required tabindex="2" />
                                                    </div>
                                              
                                                    <div class="form-group col-sm-6">
                                                        <label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
                                                        <input type="text" class="form-control " ng-model="fDataUser.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="3" /> 
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
                                                        <input type="text" class="form-control " ng-model="fDataUser.apellido_materno" placeholder="Registre su apellido materno" required tabindex="4" /> 
                                                    </div>          
                                                
                                                    <div class="form-group col-sm-6" >
                                                        <label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
                                                        <input type="email" class="form-control " ng-model="fDataUser.email" required tabindex="5" />
                                                    </div>

                                                    <div class="form-group col-sm-3" >
                                                        <label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
                                                        <select class="form-control " ng-model="fDataUser.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="6" required > </select>
                                                    </div>

                                                    <div class="form-group col-sm-3" >
                                                        <label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
                                                        <input type="text" class="form-control  mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fDataUser.fecha_nacimiento" required tabindex="7"/> 
                                                    </div>
                                                
                                                    <div class="form-group col-sm-3">
                                                        <label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
                                                        <input type="tel" class="form-control " ng-model="fDataUser.celular" placeholder="Registre su celular" ng-minlength="9" required tabindex="8" />
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label class="control-label mb-xs">Teléfono Casa  </label>
                                                        <input type="tel" class="form-control " ng-model="fDataUser.telefono" placeholder="Registre su teléfono" ng-minlength="6" tabindex="9" />
                                                    </div> 
                                                    <div class="col-sm-12 ">
                                                        <button class="btn btn-blue  pull-left" 
                                                                ng-click="btnActualizarDatosCliente(); $event.preventDefault();">
                                                                <i class="fa fa-refresh"></i> Actualizar Datos
                                                        </button>
                                                    </div>                 
                                                </div>                                                                                               
                                            </div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12"> 
                                                <div class="row"> 
                                                    <h4>Información de cuenta</h4>
                                                    <div class="col-sm-12">
                                                        <alert type="{{fAlertClave.type}}" close="fAlertClave = null" ng-show='fAlertClave.type' class="p-sm">
                                                            <strong> {{ fAlertClave.strStrong }} <i class='{{fAlertClave.icon}}'></i></strong> 
                                                            <span ng-bind-html="fAlertClave.msg"> </span>
                                                        </alert>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <div class="row">
                                                                    <div class="form-group mb-n col-md-4 col-sm-12">
                                                                        <label class="control-label mb-xs">Contraseña Actual <small class="text-danger">(*)</small> </label> 
                                                                        <input id="clave" required type="password" class="form-control " ng-model="fDataUserUsuario.clave" placeholder="Ingresa su contraseña actual" />
                                                                    </div>
                                                            
                                                                    <div class="form-group mb-n col-md-4 col-sm-12">
                                                                    <label class="control-label mb-xs">Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                                    <input id="nuevoPass" required ng-minlength="8" type="password" class="form-control " ng-model="fDataUserUsuario.claveNueva" 
                                                                        placeholder="Nueva contraseña (Min 8 caracteres)" tooltip-placement="top-left" 
                                                                        uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>
                                                                    </div>
                                                            
                                                                    <div class="form-group mb-n col-md-4 col-sm-12">
                                                                        <label class="control-label mb-xs">Confirmar Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                                        <input required ng-minlength="8" type="password" class="form-control " ng-model="fDataUserUsuario.claveConfirmar" placeholder="Confirme su nueva contraseña" />
                                                                    </div>
                                                                </div>
                                                            </div>                                                    
                                                        </div>                                                                                                   
                                                    </div>

                                                    <div class="col-sm-12 ">
                                                        <button class="btn btn-blue pull-left" 
                                                                ng-click="btnActualizarClave(); $event.preventDefault();">
                                                                <i class="fa fa-refresh"></i> Actualizar Clave
                                                        </button>
                                                    </div>                                                                                        
                                                </div>                                                                                        
                                            </div>                                                                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane " ng-class="{active: selectedTab=='1'}">
                            <div class=" panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                                <div class="panel-body">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="tab-heading col-sm-7">
                                                <span class="icon"><i class="fa fa-users"></i></span> 
                                                <div>
                                                    <span class="title">Gestionar Familiares</span> 
                                                    <p class="descripcion">Puedes agregar a tus familiares y agendar citas para ellos.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">                                          
                                            <div class="col-md-8 col-sm-12">
                                                <div class="header-parientes btn-group-btn ">
                                                    <button type="button" class="btn btn-page" ng-click="btnNuevoPariente();"><i class="fa fa-plus"></i> NUEVO</button>
                                                </div>

                                                <div class="content-parientes">
                                                    <table class="table table-responsive table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nombres</th>                                                                
                                                                <th>Apellidos</th>                                                                
                                                                <th>parentesco</th>
                                                                <th>sexo</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="row in listaParientes" >
                                                                <td>{{row.idusuariowebpariente}}</td>
                                                                <td>{{row.nombres}}</td>
                                                                <td>{{row.apellido_paterno}} {{row.apellido_materno}}</td>
                                                                <td>{{row.parentesco}}</td>
                                                                <td><span><i class="{{row.icon}}"></i></span>{{row.desc_sexo}}</td>
                                                                <td>
                                                                    <button class="btn btn-warning btn-sm"><i class="ti ti-calendar"></i></button>
                                                                    <button class="btn btn-info btn-sm" ng-click="btnEditarPariente(row);"><i class="ti ti-pencil"></i></button>
                                                                    <button class="btn btn-danger btn-sm" ng-click="btnEliminarPariente(row);"><i class="ti ti-close"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>                                                                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane " ng-class="{active: selectedTab=='2'}" ng-controller="parienteController">
                            <div class=" panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                                <div class="panel-body">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="tab-heading col-sm-7">
                                                <span class="icon"><i class="ti ti-check-box"></i></span> 
                                                <div>
                                                    <span class="title">Perfil Clínico</span> 
                                                    <p class="descripcion"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">                                              
                                                                                                                                  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>