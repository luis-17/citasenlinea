<div class="container " ng-controller="programarCitaController" ng-init="initResumenReserva();" >       
  	<div class="row" ng-if="viewResumenCita">
      	<div class="col-md-12 col-xs-12 col-sm-12 ">	              	
          	<div class="tab-heading ">
                <span class="icon"><i class="ti ti-layout-list-thumb"></i></span> 
                <div>
                    <h2 class="title">Resumen de Citas</h2> 
                    <p class="descripcion">Revisa detenidamente tu selección y realiza el pago en dos clicks.</p>
                </div>
            </div>
        </div>

      	<div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12">
      		<div class="header row-grid row-cita">
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
      			<div class="cell-grid cell-cita" style="width:10%;">
      				FECHA
      			</div>
      			<div class="cell-grid cell-cita" style="width:10%;">
      				TURNO
      			</div>
      			<div class="cell-grid cell-cita" style="width:10%;">
      				CONSULTORIO
      			</div>
      			<div class="cell-grid cell-cita" style="width:10%;">
      				PRECIO (S/.)
      			</div>
      		</div>

      		<div class="body-grid">
          		<div ng-repeat="cita in listaCitas" class="row-grid row-cita">
          			<div class="cell-grid cell-cita" style="width:17%;">
          				{{cita.busqueda.itemFamiliar.descripcion}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:14%;">
          				{{cita.busqueda.itemSede.descripcion}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:12%;">
          				{{cita.busqueda.itemEspecialidad.descripcion}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:17%;">
          				{{cita.seleccion.medico}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:10%; text-align:center;">
          				{{cita.seleccion.fecha_programada}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:10%; text-align:center;">
          				{{cita.seleccion.hora_formato}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:10%; text-align:center;">
          				{{cita.seleccion.numero_ambiente}}
          			</div>
          			<div class="cell-grid cell-cita" style="width:10%; text-align:right;">
          				{{cita.producto.precio_sede}}
          			</div>
          		</div>		
      		</div>
      		<div class="totales">
      			<div class="total total-productos ">
      				<div class="descripcion">
      					TOTAL PRODUCTOS: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_productos}}
      				</div> 
      			</div>
      			<div class="total total-servicio ">
      				<div class="descripcion">
      					USO SERVICIO WEB: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_servicio}}
      				</div> 
      			</div>
      			<div class="total total-pago ">
      				<div class="descripcion">
      					TOTAL A PAGAR: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_pago}}
      				</div>
      			</div>
      		</div>
      	</div>

      	<div class="col-md-12 col-xs-12 col-sm-12">
      		<div class="terminos-condiciones">
      		<div class="titulo" style="font-size: 17px;color: #36c5df;font-weight:600;">
      			Términos y Condiciones (Lea detenidamente esta sección)
      		</div>
      		<ul>
      			<li>
      				Luego de efectuar el pago, el dinero no será reembolsado. 
      			</li>
      			<li>
      				En caso de no poder asistir a su cita en la fecha pautada, podrá realizar una reprogramación de la misma (antes de la fecha programada). 
      			</li>
      		</ul>
          	</div>
          	<div class="botones">
          		<button class="btn btn-blue" style="width: 120px;" ng-click="pagar();" ><i class="fa fa-credit-card" style="padding: 0 5px 0 0;"></i>PAGAR</button>
          	</div>
      	</div>

      	<div class="col-md-12 col-xs-12 col-sm-12">
          <div class="call-actions mt-md">
              <div class="col-md-4 col-xs-12 col-sm-12">
                <div class="btn btn-page btn-go-citas" ng-click="goToSelCita();">
                  <i class="fa fa-angle-left"></i> PROGRAMAR OTRA CITA                             
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
                <a class="btn-go-historial" ng-click="goToHistorial();">MIRA TU 
                  <span class="historial">HISTORIAL DE CITAS</span>
                  <i class="fa fa-angle-right"></i>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
          </div>
        </div>
  	</div>

  	<div class="row" ng-if="viewResumenCompra">
        <div class="col-md-12 col-xs-12 col-sm-12 ">                  
          <div class="tab-heading ">
              <span class="icon"><i class="ti ti-layout-list-thumb"></i></span> 
              <div>
                  <h2 class="title">Resumen de Compra</h2> 
                  <p class="descripcion">Descarga e imprime tus tickets para el día de tu cita.</p>
              </div>
          </div>
        </div>

        <div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12">
          <div class="header row-grid row-cita">
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
            <div class="cell-grid cell-cita" style="width:12%;">
              <i class="fa fa-download"></i>
            </div>
          </div>

          <div class="body-grid" style="min-height: 100px;">
            <div ng-repeat="cita in fSessionCI.listaCitasGeneradas" class="row-grid row-cita">
              <div class="cell-grid cell-cita" style="width:17%;">
                {{cita.busqueda.itemFamiliar.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:14%;">
                {{cita.busqueda.itemSede.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:12%;">
                {{cita.busqueda.itemEspecialidad.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:17%;">
                {{cita.seleccion.medico}}
              </div>
              <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                {{cita.seleccion.fecha_programada}}
              </div>
              <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                {{cita.seleccion.hora_formato}}
              </div>
              <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                {{cita.seleccion.numero_ambiente}}
              </div>
              <div class="cell-grid cell-cita" style="width:12%; text-align:center;">
                <span class="nro-doc" ng-click="descargaComprobante(cita);">
                  <i class="fa fa-file-pdf-o"></i>
                  <a href=""> nro-123654 </a>
                </span>
              </div>
            </div>    
          </div>
        </div>

        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="call-actions mt-md">
              <div class="col-md-4 col-xs-12 col-sm-12">
                <div class="btn btn-page btn-go-citas" ng-click="goToSelCita();">
                  <i class="fa fa-angle-left"></i> PROGRAMAR OTRA CITA                             
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
                <a class="btn-go-historial" ng-click="goToHistorial();">MIRA TU 
                  <span class="historial">HISTORIAL DE CITAS</span>
                  <i class="fa fa-angle-right"></i>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
          </div>
        </div>
    </div>
</div>