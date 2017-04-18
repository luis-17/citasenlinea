<div class="content container" ng-controller="programarCitaController" ng-init="initResumenReserva();" >       
      <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
          <div class="col-md-12 col-xs-12 col-sm-12">
              <div class="row">
	              <div class="col-md-8 col-md-offset-2 col-xs-12 col-sm-12 ">
	              	<div class="row">
		              	<div class="col-md-12 col-xs-12 col-sm-12 ">	              	
			              	<div class="tab-heading ">
		                        <span class="icon"><i class="ti ti-layout-list-thumb"></i></span> 
		                        <div>
		                            <span class="title">Resumen de Citas</span> 
		                            <p class="descripcion">Revisa detenidamente tu selección y realiza el pago en dos clicks.</p>
		                        </div>
		                    </div>
	                    </div>

		              	<div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12">
		              		<div class="header row-grid row-cita">
		              			<div class="cell-grid cell-cita" style="width:180px;">
		              				CITA PARA
		              			</div>
		              			<div class="cell-grid cell-cita" style="width:140px;">
		              				SEDE
		              			</div>
		              			<div class="cell-grid cell-cita">
		              				ESPECIALIDAD
		              			</div>
		              			<div class="cell-grid cell-cita" style="width:180px;">
		              				MÉDICO
		              			</div>
		              			<div class="cell-grid cell-cita">
		              				FECHA
		              			</div>
		              			<div class="cell-grid cell-cita">
		              				TURNO
		              			</div>
		              			<div class="cell-grid cell-cita">
		              				PRECIO (S/.)
		              			</div>
		              		</div>

		              		<div class="body-grid">
			              		<div ng-repeat="cita in listaCitas" class="row-grid row-cita">
			              			<div class="cell-grid cell-cita" style="width:180px;">
			              				{{cita.busqueda.itemFamiliar.descripcion}}
			              			</div>
			              			<div class="cell-grid cell-cita" style="width:140px;">
			              				{{cita.busqueda.itemSede.descripcion}}
			              			</div>
			              			<div class="cell-grid cell-cita">
			              				{{cita.busqueda.itemEspecialidad.descripcion}}
			              			</div>
			              			<div class="cell-grid cell-cita" style="width:180px;">
			              				{{cita.seleccion.medico}}
			              			</div>
			              			<div class="cell-grid cell-cita">
			              				{{cita.seleccion.fecha_programada}}
			              			</div>
			              			<div class="cell-grid cell-cita">
			              				{{cita.seleccion.hora_formato}}
			              			</div>
			              			<div class="cell-grid cell-cita">
			              				{{cita.producto.precio_sede}}
			              			</div>
			              		</div>		
		              		</div>
		              		<div class="totales">
		              			<div class="total-productos row-grid row-cita">TOTAL PRODUCTOS: s/. {{totales.total_productos}}</div>
		              			<div class="total-servicio row-grid row-cita">USO SERVICIO WEB: s/. {{totales.total_servicio}}</div>
		              			<div class="total-pago row-grid row-cita">TOTAL A PAGAR: s/. {{totales.total_pago}}</div>
		              		</div>
		              	</div>

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
	              </div>	              	              
              </div>
          </div>
      </div>
</div>