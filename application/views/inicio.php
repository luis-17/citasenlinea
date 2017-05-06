<div class=" " ng-controller="inicioController">       
	<div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
		<div class="row dashboard ">
			<div class="col-md-12 col-xs-12 col-sm-12 ">
				<div ng-if="fSessionCI.sexo=='M'" class="saludo"> ¡BIENVENIDO {{fSessionCI.nombres}} {{fSessionCI.apellido_paterno}}! </div>
				<div ng-if="fSessionCI.sexo=='F'"  class="saludo"> ¡BIENVENIDA {{fSessionCI.nombres}} {{fSessionCI.apellido_paterno}}! </div>
			</div>
			<div class="col-md-10 col-md-offset-1 col-xs-12 col-sm-12">
				<div class="valores panel">
					<div class="contenedor-items-valores">
						<div class="item">
							<div class="box-item">
								<div class="imagen">
									<img src="{{ dirImages + 'dashboard/icon-peso.png' }}"  />
								</div>
								<div class="value">
									<span class="title">Peso</span>
									<div class="data">{{fSessionCI.peso}} <span class="medida" >Kg.</span></div>
								</div>
							</div>
						</div>
						<div class="item">
						<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-estatura.png' }}"  />
							</div>
							<div class="value">
								<span class="title">Estatura</span>
								<div class="data">{{fSessionCI.estatura}} <span class="medida" >Mts.</span></div>
							</div>
						</div>
						</div>
						<div class="item imc" uib-tooltip="Índice de Masa Corporal (IMC): {{fSessionCI.imc.tipo}}" tooltip-placement="top">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-imc.png' }}"  />
							</div>
							<div class="value" >
								<span class="title">IMC</span>
								<span class="alerta" style="background:{{fSessionCI.imc.color}};" ng-class="{animation: fSessionCI.imc.dato < 18 || fSessionCI.imc.dato > 24.9}" ></span>
								<div class="data" style="color:{{fSessionCI.imc.color}};" >
									{{fSessionCI.imc.dato}} <!-- <span class="medida" ></span> -->
								</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-tipo-sangre.png' }}"  />
							</div>
							<div class="value">
								<span class="title">Tipo de Sangre</span>
								<div class="data">{{fSessionCI.tipo_sangre.descripcion}}</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-sexo-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
							</div>
							<div class="value">
								<span class="title">Sexo</span>
								<div class="data">{{fSessionCI.sexo}}</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-edad-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
							</div>
							<div class="value">
								<span class="title">Edad</span>
								<div class="data">{{fSessionCI.edad}} <span class="medida" >años</span></div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 ">
				<div class="div-btn">
					<div class="btn-go-perfil" ng-click="goToPerfil();">VER MI PERFIL <i class="fa fa-angle-right"></i></div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 ">
				<div class="col-md-offset-1 col-md-5 col-xs-12 col-sm-12 ">
					<div class="caja caja-1 panel" style="">
						<div class="division division-1">	
							<div class="texto">
								PROGRAMA TUS CITAS</br>
								<span class="familiares" style="color: #0f7986;">Y DE TUS FAMILIARES</span></br>
								<p class="pasos">Es muy sencillo... Selecciona una fecha, escoge la sede, la especialidad y un médico.</p>
							</div>
							<div class="btn btn-default btn-go-citas" ng-click="goToSelCita();">PROGRAMAR CITA <i class="fa fa-angle-right"></i></div>
						</div>

						<div class="division division-2">	              				
							<div class="citas pendientes" ng-click="goToHistorial();">
								<spam class="count-citas">{{fSessionCI.citas_pendientes}}</spam>
								<spam class="tipo">Citas </br>Pendientes</spam>
							</div>
							<div class="citas realizadas" ng-click="goToHistorial();">
								<spam class="count-citas">{{fSessionCI.citas_realizadas}}</spam>
								<spam class="tipo">Citas</br>Realizadas</spam>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5 col-md-offset-right-1 col-xs-12 col-sm-12 ">
					<div class="caja caja-2 panel" >	              			
						<div class="division division-1">	              				
							<div class="imagen">
								<img class src="{{ dirImages + 'dashboard/historial-citas.png' }}" />
							</div>	              				
							<div class="texto" ng-click="goToHistorial();">MIRA TU 
								<span class="historial">HISTORIAL DE CITAS</span>
								<i class="fa fa-angle-right"></i>
								<i class="fa fa-angle-right"></i>
							</div>
						</div>

						<div class="division  division-2">	
							<div class="texto" ng-click="goToResultados();">ACCEDE A TUS </br>
								<i class="fa fa-angle-right"></i>
								<span class="resultados">RESULTADOS DE LABORATORIO</span>
								<i class="fa fa-angle-left"></i>
							</div>
							<div class="imagen">
								<img class src="{{ dirImages + 'dashboard/historial-laboratorio.png' }}" />
							</div>	              				              				              				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>