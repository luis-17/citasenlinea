<div class="content" ng-controller="historialCitasController">       
  <div class="filtros historial btn-group-btn pl-n ml-n ">
    <ul class="demo-btns">
      <li class="" >
        <select class="form-control " ng-model="fBusqueda.familiar"
            ng-change="listarHistorial();" 
            ng-options="item.descripcion for item in listaFamiliares">
          </select>
      </li>
      <li class="" >
        <select class="form-control " ng-model="fBusqueda.sede"
          ng-change="listarEspecialidad();listarHistorial();" 
          ng-options="item.descripcion for item in listaSedes">
        </select>
      </li>
      <li class="" >
        <select class="form-control " ng-model="fBusqueda.especialidad"
          ng-change="listarHistorial();" 
          ng-options="item.descripcion for item in listaEspecialidad">
        </select>
      </li>
      <li class="" style="top: 1px;" uib-tooltip="Haz click para cambiar de estado de cita" tooltip-placement="bottom">
          <input class="form-control tgl tgl-flip" id="cb5" type="checkbox" ng-model="fBusqueda.tipoCita" ng-change="cambiarVista();"
                  ng-true-value="'realizadas'" ng-false-value="'pendientes'"
                   />
          <label class="tgl-btn" data-tg-off="Citas Pendientes > " data-tg-on="< Citas Realizadas" for="cb5" style="margin-bottom: 0px;" />
      </li>
    </ul>
  </div>
  <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">    
    <div class="col-sm-12 col-md-12 col-xs-12" style="margin-top: 20px;">
      <div class="row">
        <div class="col-sm-12 col-md-10 pl-n" >
          <div class="col-sm-12 col-md-12 col-xs-12">            
            <div class="text-guia historial">
              <p class="saludo mb-xs">¡Hola {{fSessionCI.nombres}}!</p>
              <p class="instruccion">Mira tu historial de citas... Próximas y Pasadas. Utiliza los filtros para ubicarlas más rápido!</p>
            </div>
          </div>

          <div class="col-sm-12 col-md-12 col-xs-12">  
            <div class="alert alert-warning" ng-show="listaDeCitas.length == 0">
              No hay citas registradas con las opciones seleccionadas, intenta con otros parámetros... 
            </div>
          </div>
          <div class="historial-citas" >
            <div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12">
              <div class="body-grid" style="min-height: 100px;" ng-show="listaDeCitas.length > 0">
                <div class="header row-grid row-cita" style="position: fixed;width: {{width}}%;">
                  <div class="cell-grid cell-cita" style="width:17%;">
                    CITA PARA
                  </div>
                  <div class="cell-grid cell-cita" style="width:14%;">
                    SEDE
                  </div>
                  <div class="cell-grid cell-cita" style="width:12%;">
                    ESPECIALIDAD
                  </div>
                  <div class="cell-grid cell-cita" style="width:17%;">
                    MÉDICO
                  </div>
                  <div class="cell-grid cell-cita" style="width:9%;">
                    FECHA
                  </div>
                  <div class="cell-grid cell-cita" style="width:9%;">
                    TURNO
                  </div>
                  <div class="cell-grid cell-cita" style="width:10%;">
                    CONSULTORIO
                  </div>
                  <div class="cell-grid cell-cita" style="width:9%;">
                    <i class="fa fa-download"></i>
                  </div>
                  <div class="cell-grid cell-cita" style="width:3%;">
                    <i class="fa "></i>
                  </div>
                </div>
                <div ng-repeat="cita in listaDeCitas" class="row-grid row-cita">
                    <div class="cell-grid cell-cita" style="width:17%;text-transform: uppercase;">
                      {{cita.paciente}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:14%;">
                      {{cita.itemSede.sede}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:12%;">
                      {{cita.itemEspecialidad.especialidad}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:17%;">
                      {{cita.itemMedico.medico}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                      {{cita.fecha_formato}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                      {{cita.hora_inicio_formato}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                      {{cita.itemAmbiente.numero_ambiente}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:9%; text-align:center;cursor: pointer;">
                      <span class="nro-doc" ng-click="descargaComprobante(cita);">
                        <i class="fa fa-file-pdf-o"></i>
                        <a href=""> nro-123654 </a>
                      </span>
                    </div>
                    <div class="cell-grid cell-cita" style="width:3%; text-align:center;cursor: pointer;">
                      <span class="reprog" ng-click="reprogramarCita(cita);" >
                        <i class="{{cita.icon_cita}}" style="color:{{cita.color_cita}};"></i>                      
                      </span>
                    </div>
                </div>    
              </div>
            </div>
            <div class="clearfix"></div>
          </div>           
        </div>
        <div class="col-sm-12 col-md-2 pl-n">
          <div class="col-sm-12 col-md-12 col-xs-12" ng-show="fSessionCI.listaCitas.length > 0">            
            <div class="text-guia noanimate">              
              <p class="instruccion">{{fSessionCI.nombres}}, finaliza tu compra!... Haz click en "FINALIZAR"</p>
              <p class="saludo">Villa Salud, Te Cuida!</p>
            </div>
          </div>
          <div class="citas scroll-pane" ng-show="fSessionCI.listaCitas.length > 0">
            <ul class="list-citas" style="height: 350px;">
                <li ng-repeat="(index,fila) in fSessionCI.listaCitas" class="item-list-citas notification-{{fila.clase}}" >
                    <div class="cita" ng-click="" style="">
                      <span class="eliminar" ng-click="quitarDeLista(index,fila);"><i class="fa fa-times" style="color: #ce1d19;"></i></span>  
                      <div><i class="fa fa-stethoscope" style="color: #36c0d1;"></i>  Cita para:    <span class="cita-familiar">{{fila.busqueda.itemFamiliar.descripcion}}</span></div>
                      <div><i class="fa fa-hospital-o"  style="color: #ce1d19;"></i>  Sede:         <span class="cita-sede">{{fila.busqueda.itemSede.descripcion}}</span></div>
                      <div><i class="ti ti-slice"       style="color: #ffc107;"></i>  Especialidad: <span class="cita-esp">{{fila.busqueda.itemEspecialidad.descripcion}}</span></div>
                      <div><i class="fa fa-user-md"     style="color: #191970;"></i>  Médico:       <span class="cita-medico">{{fila.seleccion.medico}}</span></div>
                      <div><i class="fa fa-clock-o"     style="color: #929191;"></i>  Turno:        <span class="cita-turno">{{fila.seleccion.fecha_programada}} {{fila.seleccion.hora_formato}}</span></div>
                    </div>                            
                </li>
            </ul>
            <div class="boton-finalizar">
              <a class="" ng-click="resumenReserva();">FINALIZAR  <i class="fa fa-angle-right"></i>
              </a>
            </div>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>