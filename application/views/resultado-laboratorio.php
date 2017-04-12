<style type="text/css">
  .glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
  }


</style>
<section class="page-title">
    <div class="container clearfix">
        <div class="row">
            <div class="col-md-6 pl-n" >
                <h2 class="text-primary"><span><i class="fa fa-flask"></i></span> Resultados de Laboratorio</h2>
            </div>
            <div class="col-md-6" >
                <ol class="breadcrumb m-n pull-right">
                  <li><a href="#/">Inicio</a></li>
                  <li>Resultados de Laboratorio</li>
                </ol>
            </div>
        </div>
    </div>
</section> 
<div class="container" ng-controller="resultadolaboratorioController" >
      <div class="row"><!-- grid de ordenes -->
          <div class="last right-text-pad">
            <blockquote>
              <p class="text-primary">Lista General de Resultados </p>
              <small class="text-primary">Paciente : <ins><strong class="text-teals">{{ fSessionCI.nombres }} {{ fSessionCI.apellido_paterno}} {{ fSessionCI.apellido_materno }} </strong></ins></small>              
            </blockquote>
            
          </div>
          <div class="panel" data-widget='{"draggable": "false"}' ng-show="vistabla1"> <!-- grid de ordenes -->
            <div class="panel-body" style="height: 200px">
              <div class="col-md-12 col-sm-12">
                <table id="grid" class="mb-md"></table> 
              </div>
              <div class="row text-center" ng-show="vistabla2">
                  <label class="label label-primary p-sm"><i class="fa fa-circle-o-notch fa-spin" style="font-size:18px"></i> Cargando Resultados ...</label>
              </div>                                                                             
            </div>
          </div>
          <div class="panel" data-widget='{"draggable": "false"}' ng-show="vistabla3"> <!-- grid de ordenes -->
            <div class="panel-body" style="height: 400px">
              <div class="col-md-12 col-sm-12">
                <button class="btn btn-danger mb-md" ng-click="volver();"><i class="fa fa-arrow-left"></i> Volver </button>
                <table id="gridDetail"></table> 
              </div>                                                                             
            </div>
          </div> 
                     
      </div>

</div>