<div class="content container page-login"  ng-controller="loginController">
    <style type="text/css">
        .page-content {
            padding: 0;
            /*background: url("{{ dirImages + 'dinamic/empresa/banner-login.jpg'  }}") no-repeat left ;*/
        }
    </style>
    <div class="row">
        <div class="capa-info" >            
            <div class="col-sm-12 col-md-6">            
                <div class="info-heading">
                    Gestiona tus citas y las de tus familiares, desde la comodidad de tu hogar.
                    <!-- <a href="" class="btn btn-page" ng-click="btnRegistroEnSistema(); $event.preventDefault();">Registrarse</a>  -->                                  
                </div>
                <div class="info-subheading">
                    Disfruta los beneficios de ser un paciente de Villa Salud... 
                </div>
                <div class="info-lema">
                    Villa Salud, Te Cuida!
                </div>                
            </div>

            <div class="formulario formulario-login" ng-show="!viewRegister">
                <div class="col-sm-12 col-md-4" > 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bienvenido
                        </div>
                        <div class="panel-body">                        
                            <form action="" class="form-horizontal" id="validate-form">
                                <div class="form-group mb-md">
                                    <div class="col-xs-12">
                                        <input ng-model="fLogin.usuario" type="text" class="form-control" placeholder="N° documento" data-parsley-minlength="6" required focus-me enter-as-tab/>
                                        
                                    </div>
                                </div>
                                <div class="form-group mb-md">
                                    <div class="col-xs-12">
                                        <input ng-model="fLogin.clave" type="password" class="form-control" id="exampleInputPassword1" placeholder="Clave" required ng-enter="btnLoginToSystem(); $event.preventDefault();"/>
                                        
                                    </div>
                                </div>                       

                                <!--<div class="form-group mb-n">
                                    <div class="col-xs-12">
                                        <div id="recaptcha-login" class="g-recaptcha" data-ng-controller="loginController" data-ng-init="initLoginRecaptcha()"
                                            data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div> 
                                    </div>
                                </div>  -->

                                <alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                                    <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                                </alert>               
                                                      
                            </form>
                        </div>
                        
                        <div class="panel-footer">
                            <div class="clearfix">
                                <!-- <a href="#/extras-registration" class="btn btn-default pull-left">Register</a> -->
                                <!-- <a href="" class="btn btn-page pull-right" ng-click="btnLoginToSystem()" ng-disabled="habilitaBtn" >Iniciar sesión</a> -->
                                <button class="btn btn-page" style="width:100%;"  ng-click="btnLoginToSystem(); $event.preventDefault();" > Iniciar sesión </button> 
                            </div>

                            <div class="col-xs-12 set-password">
                                <a href="extras-forgotpassword.html" class="link-password">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                        <div class="col-xs-12 btn-registro">
                            <a href="" ng-click="btnViewRegister(); $event.preventDefault();">
                                ¿No tienes cuenta? Regístrate Aquí <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>            
                </div>
            </div>

            <div class="formulario formulario-registro" ng-show="viewRegister" ng-controller="usuarioController" ng-init="initRegistrarUsuario();">
                <div class="col-sm-12 col-md-5" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a style="position: relative;left: -20%; color:#36c0d1;" ng-click="btnViewLogin();"><i class="fa fa-angle-left"></i></a>{{ titleForm }} 
                        </div>
                        <div class="panel-body">
                            <form class="" name="formUsuario" novalidate>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs"> DNI ó Documento de Identidad </label> -->
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm" ng-model="fData.num_documento" placeholder="Ingresa tu DNI ó Documento de Identidad" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/"/> 
                                            <div class="input-group-btn ">
                                                <button type="button" class="btn btn-default btn-sm" ng-click="verificarDoc(); $event.preventDefault();" ><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <!-- <input ng-init="verificaDNI();" type="text" class="form-control input-sm" ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/" ng-change="verificaDNI();" />  -->
                                    </div>
                                    

                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label> -->
                                        <input type="text" class="form-control input-sm" ng-model="fData.nombres" placeholder="Ingresa tus nombre" required tabindex="2" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label> -->
                                        <input type="text" class="form-control input-sm" ng-model="fData.apellido_paterno" placeholder="Ingresa tu apellido paterno" required tabindex="3" /> 
                                    </div>
                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label> -->
                                        <input type="text" class="form-control input-sm" ng-model="fData.apellido_materno" placeholder="Ingresa tu apellido materno" required tabindex="4" /> 
                                    </div>          
                                </div>      

                                <div class="row">
                                    <div class="form-group mb-md col-md-6" >
                                        <!-- <label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label> -->
                                        <input type="email" class="form-control input-sm" ng-model="fData.email" placeholder="Ingresa tu e-mail" required tabindex="5" />
                                    </div>                                   

                                    <div class="form-group mb-md col-md-6" >
                                        <!-- <label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>   -->
                                        <input type="text" class="form-control input-sm mask" data-inputmask="'alias': 'dd-mm-yyyy'" placeholder="Ingresa tu fecha de nacimiento" ng-model="fData.fecha_nacimiento" required tabindex="7"/> 
                                    </div>
                                </div>

                                <div class="row">                                   

                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label> -->
                                        <input type="tel" class="form-control input-sm" ng-model="fData.celular" placeholder="Ingresa tu celular" ng-minlength="9" required tabindex="8" />
                                    </div>
                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Teléfono Casa  </label> -->
                                        <input type="tel" class="form-control input-sm" ng-model="fData.telefono" placeholder="Ingresa tu teléfono" ng-minlength="6" tabindex="9" />
                                    </div>
                                    <div class="form-group mb-md col-md-6" >
                                        <!-- <label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label> -->
                                        <select class="form-control input-sm" ng-model="fData.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="6" required > </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Contraseña <small class="text-danger">(*)</small> </label> -->
                                        <input type="password" class="form-control input-sm" ng-model="fData.clave" placeholder="Contraseña" ng-minlength="8" 
                                               required tabindex="10" tooltip-placement="top-left" 
                                               uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/> 
                                    </div>

                                    <div class="form-group mb-md col-md-6">
                                        <!-- <label class="control-label mb-xs">Repita Contraseña <small class="text-danger">(*)</small> </label> -->
                                        <input type="password" class="form-control input-sm" ng-model="fData.repeat_clave" placeholder="Repita Contraseña" 
                                               ng-minlength="8" required tabindex="11" tooltip-placement="top-left" 
                                               uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>  
                                    </div>                  
                                </div>

                                <div class="row">
                                    <div class="form-group mb-md col-md-6">
                                        <div id="recaptcha-registro" data-ng-controller="usuarioController" data-ng-init="initRecaptchaReg()"
                                                class="g-recaptcha" data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-12">
                                        <alert type="{{fAlert.type}}" close="fAlert = null" ng-show='fAlert.type' class="p-sm mb-n" style="margin-right: 12px;">
                                            <strong> {{ fAlert.strStrong }} <i class='{{fAlert.icon}}'></i></strong> 
                                            <span ng-bind-html="fAlert.msg"> </span>
                                        </alert>
                                    </div>
                                </div>


                            </form>
                        </div>

                        <div class="col-xs-12 btn-registro">
                            <a href="" ng-click="registrarUsuario(); $event.preventDefault();" ng-disabled="formUsuario.$invalid" tabindex="14">
                                REGISTRARME 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>