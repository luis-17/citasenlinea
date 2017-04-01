<section class="page-title" id="login-form">
    <div class="container clearfix">
        <div class="row">
            
        </div>
    </div>
</section> 
<div class="content container"  ng-controller="loginController">
    <div class="row">
        <div class="col-md-4 col-md-offset-2"> 
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Iniciar sesión </h2>
                </div>
                <div class="panel-body">                        
                    <form action="" class="form-horizontal" id="validate-form">
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">                           
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input ng-model="fLogin.usuario" type="text" class="form-control" placeholder="E-mail" data-parsley-minlength="6" required focus-me enter-as-tab/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input ng-model="fLogin.clave" type="password" class="form-control" id="exampleInputPassword1" placeholder="Clave" required ng-enter="btnLoginToSystem()"/>
                                </div>
                            </div>
                        </div>                       

                        <div class="form-group mb-n">
                            <div class="col-xs-12">
                                <div id="recaptcha-login" class="g-recaptcha" data-ng-controller="loginController" data-ng-init="initRecaptcha()"
                                    data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div> 
                            </div>
                        </div> 

                        <alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                            <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                        </alert>               
                                              
                    </form>
                </div>
                
                <div class="panel-footer">
                    <div class="clearfix">
                        <!-- <a href="#/extras-registration" class="btn btn-default pull-left">Register</a> -->
                        <!-- <a href="" class="btn btn-page pull-right" ng-click="btnLoginToSystem()" ng-disabled="habilitaBtn" >Iniciar sesión</a> -->
                        <button class="btn btn-page pull-right"  ng-click="btnLoginToSystem(); $event.preventDefault();" > Iniciar sesión </button> 
                    </div>

                    <div class="form-group mb-n">
                        <div class="col-xs-12">
                            <a href="extras-forgotpassword.html" class="pull-left link-password">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div> 
                </div>
            </div>            
        </div>
        <div class="col-md-4">            
            <div class="panel panel-default" style="border: 1px solid #ce1d19;">
                <div class="panel-heading" style="min-height: 24px;" ></div>
                <div class="panel-body" style="text-align: center;">                        
                    <p style="font-size:16px;">Disfruta los beneficios de ser un paciente de <br/>Villa Salud... Gestiona tus citas y las de tus familiares, desde la comodidad de tu hogar.</p>
                    <a href="" class="btn btn-page" ng-click="btnRegistroEnSistema()">Registrarse</a>                
                    <div>                    
                        <h4 style="color:#ef635f;">Villa Salud, Te Cuida!</h4>
                    </div>
                </div>
                <div class="panel-footer" >                    
                </div>
            </div>
        </div>
    </div>
</div>