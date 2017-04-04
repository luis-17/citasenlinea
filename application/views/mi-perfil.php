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
<div class="content container"  ng-controller="usuarioController" ng-init="init();" >
    <div data-widget-group="group1" class="ui-sortable">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel panel-profile" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                      <div class="panel-body">
                        <img  masked-image="" class="img-circle" ng-src="{{ dirImages + 'dinamic/usuario/' + fSessionCI.nombre_imagen }}" alt=" {{ fSessionCI.username }} " /> 
                        <a href class="btn btn-default btn-sm" ng-click="btnCambiarMiFotoPerfil(fData,fSessionCI);" style="top: 5px;position: relative;">
                            <i class="ti ti-pencil"></i> Cambiar
                        </a>               
                      </div>                      
                    </div>

                    <div class="panel panel-profile score-puntos" >
                        
                    </div>
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
                                                <div class="row">                                                    
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
                                                            <input type="email" class="form-control input-sm" ng-model="fData.email" required disabled tabindex="5" />
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

                                                <alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                                                    <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                                                </alert>                                               
                                            </div>

                                            <div class="col-sm-12 ">
                                                <button class="btn btn-page btn-sm pull-right" 
                                                        ng-click="btnActualizarDatosCliente(); $event.preventDefault();">
                                                        <i class="fa fa-refresh"></i> Actualizar
                                                </button>
                                            </div>

                                            <h4>Información de cuenta</h4>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="form-group col-sm-12">
                                                        <div class="row">
                                                            <div class="form-group mb-n col-md-4 col-sm-12">
                                                                <label class="control-label mb-xs">Contraseña Actual <small class="text-danger">(*)</small> </label> 
                                                                <input id="clave" required type="password" class="form-control input-sm" ng-model="fDataUsuario.clave" placeholder="Ingresa su contraseña actual" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <div class="row">
                                                            <div class="form-group mb-n col-md-4 col-sm-12">
                                                            <label class="control-label mb-xs">Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                            <input id="nuevoPass" required ng-minlength="8" type="password" class="form-control input-sm" ng-model="fDataUsuario.claveNueva" 
                                                                placeholder="Nueva contraseña (Min 8 caracteres)" tooltip-placement="top-left" 
                                                                uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <div class="row">
                                                            <div class="form-group mb-n col-md-4 col-sm-12">
                                                                <label class="control-label mb-xs">Confirmar Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                                <input required ng-minlength="8" type="password" class="form-control input-sm" ng-model="fDataUsuario.claveConfirmar" placeholder="Confirme su nueva contraseña" />
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>  

                                                <alert type="{{fAlertClave.type}}" close="fAlertClave = null" ng-show='fAlertClave.type' class="p-sm">
                                                    <strong> {{ fAlertClave.strStrong }} <i class='{{fAlertClave.icon}}'></i></strong> 
                                                    <span ng-bind-html="fAlertClave.msg"> </span>
                                                </alert>
                                                                                             
                                            </div>

                                            <div class="col-sm-12 ">
                                                <button class="btn btn-page btn-sm pull-right" 
                                                        ng-click="btnActualizarClave(); $event.preventDefault();">
                                                        <i class="fa fa-refresh"></i> Actualizar Clave
                                                </button>
                                            </div>                                                                                        
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