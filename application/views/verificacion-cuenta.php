<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="http://www.villasalud.pe/villasalud/wp-content/uploads/bfi_thumb/gm-32qs3td1tuam0as4xkuf4a.png" />
    <title>Sistema de Citas en Linea | Villa Salud</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Villa Salud">
    <meta name="author" content="Villa Salud">

    <link rel="stylesheet" href="../../assets/css/fuentes.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />    

    <link rel="stylesheet" href="../../bower_components/animate.css/animate.css" />

    <link rel="stylesheet" href="../../bower_components/skylo/vendor/styles/skylo.css" />
    <link rel="stylesheet" href="../../bower_components/themify-icons/themify-icons.css" />

    <!--[if lt IE 10]>
        {{ "js/media.match.min.js" | asset_url | script_tag }}
        {{ "js/respond.min.js" | asset_url | script_tag }}
        {{ "js/placeholder.min.js" | asset_url | script_tag }}
    <![endif]-->
    <link rel="stylesheet" href="../../assets/css/custom.css">


    <!-- prochtml:remove:dist -->
    <!--<script type="text/javascript"> less = { env: 'production' }; </script>
    <script type="text/javascript" src="assets/plugins/misc/less.js"></script>-->
    <!-- /prochtml -->
    
</head>

<body>
    <header id="topnav" class="navbar" role="banner" ng-cloak >
        <section class="page-header logo-left secondary-page" >
            <div class="container col-md-12 ">
                <div class="clearfix row">
                    <div class="col-md-3 col-md-offset-3 col-sm-12">
                        <div class="logo-area">
                            <a class="navbar-brand" >Villa Salud</a>
                            <div class="toolbar-icon-bg hidden-xs" id="toolbar-search" ng-class="{active: getLayoutOption('showSmallSearchBar')}">
                                <div class="input-group">
                                    <!-- <span class="input-group-btn"><button class="btn" type="button"><i class="ti ti-search"> ESPECIALIDAD: </i></button></span> -->
                                    <!-- <input type="text" class="form-control" placeholder="Search...">  -->
                                    <span class="input-group-btn">
                                        <button class="btn" type="button" ng-click="toggleSearchBar($event)"><i class="ti ti-close"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="lema-area">
                            <a class="texto">CITAS EN LÍNEA</a>
                            <!-- <div class="lema pb-xs" >Villa Salud, Te Cuida!</div> -->
                        </div>
                    </div>                  
                </div>
            </div>
        </section>
        <div class="page-separador"></div>    
    </header>
    <style>
        #topnav .navbar-brand{
          background: url("../../assets/img/dinamic/empresa/logo-250x60.png") no-repeat left 0 center;
          background-size: contain;
          width: 250px;
          height: 60px;
        }

    </style>

    <div id="wrapper">
        <div id="layout-static">
            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                        <div class="col-md-6 col-md-offset-3 col-sm-12" style="padding-top:15px;">
                        
                            <div class="tab-heading">
                                <span class="icon"><i class="ti ti-check"></i></span> 
                                <div>
                                    <h2 class="title">Ha sido activada tu cuenta satisfactoriamente</h2> 
                                    <p class="descripcion">Comienza a disfrutar los beneficios de ser un paciente de Villa Salud</p>
                                </div>
                            </div>
                            <div class="col-sm-12">                                                                                           
                                <div class=""> 

                                </div>
                            </div>                            
                        </div>

                        <!-- <div class="col-md-10 col-md-offset-1 col-sm-12" style="padding-top:20%">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <div class="call-actions mt-md">
                                    <div class="col-md-4 col-xs-12 col-sm-12">
                                        <div class="btn btn-page btn-go-citas">
                                            <i class="fa fa-angle-left"></i> PROGRAMAR CITAS                             
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-xs-12 col-sm-12">
                                        <a href="http://www.villasalud.pe" target="_blank"> 
                                            <span class="lema" >
                                                Villa Salud, Te Cuida!
                                            </span>
                                        </a>                            
                                    </div>

                                    <div class="col-md-4 col-xs-12 col-sm-12">
                                        <a class="btn-go-historial">MIRA TUS 
                                            <span class="historial">RESULTADOS DE LABORATORIO</span>
                                            <i class="fa fa-angle-right"></i>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
                    </div>
                </div>
                <footer role="contentinfo" ng-show="!layoutLoading" ng-cloak>
                    <div class="container">
                        <div class="clearfix">
                            <ul class="list-unstyled list-inline pull-left">
                                <li>
                                    <h6 style="">Copyrights &copy; 2017: Villa Salud - Perú</h6>
                                </li>
                            </ul>
                            <button class="pull-right btn btn-link btn-xs hidden-print" back-to-top><i class="ti ti-arrow-up"></i></button>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

</body>
</html>