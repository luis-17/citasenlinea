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
									<div class="title">Peso</div>
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
								<div class="title">Estatura</div>
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
								<div class="title">IMC</div>
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
								<div class="title">Tipo de Sangre</div>
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
								<div class="title">Sexo</div>
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
								<div class="title">Edad</div>
								<div class="data">{{fSessionCI.edad}} <span class="medida" >años</span></div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12 col-sm-6 ">
				<div class="div-btn div-btn-1">					
					<div class="btn btn-default btn-go-citas" style="width: 200px;" ng-click="goToSelCita();">PROGRAMAR CITA <i class="fa fa-angle-right"></i></div>
				</div>
			</div>			
			<div class="col-md-6 col-xs-12 col-sm-6 ">
				<div class="div-btn div-btn-2">					
					<div class="btn-go-perfil" ng-click="goToPerfil();">ACTUALIZAR MI PERFIL <i class="fa fa-angle-right"></i></div>
				</div>
			</div>
			<div class="col-md-10 col-md-offset-1 col-xs-12 col-sm-12 mb-lg mt-lg">				
				<div class="tips-villa-salud" ng-if="fSessionCI.sexo.toLowerCase() == 'f'">
					<div class="col-md-12 col-xs-12 col-sm-12" style="text-align:center;">
					<!-- <h4 class="heading-tips">Queremos cuidarte...</h4> -->
						<span ng-if="fSessionCI.sexo=='M'"> Estimado {{fSessionCI.paciente}} </span>
						<span ng-if="fSessionCI.sexo=='F'"> Estimada {{fSessionCI.paciente}} </span>, tu salud nos importa, por ello te sugerimos realizarte los siguientes análisis y/o procedimientos que según tu perfil consideramos importantes para que puedas gozar de buena salud SIEMPRE!
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.imc.dato < 18 || fSessionCI.imc.dato > 24.9">
						<div class="tip mb-xs">
							<span>Chequea tu peso, Tu IMC no es normal</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,27);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
						<div class="tip mb-xs">
							<span>Papanicolau</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
						<div class="tip mb-xs">
							<span>Coloscopia</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
						<div class="tip mb-xs">
							<span>Ecografía Transvaginal</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad >= 20 && fSessionCI.edad <= 35">
						<div class="tip mb-xs">
							<span>Ecografía de mamas</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
						</div>
					</div>					
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
						<div class="tip mb-xs">
							<span>Mamografía</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad >= 20 && fSessionCI.edad <= 35">
						<div class="tip mb-xs">
							<span>Examen Médico General</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,65);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad > 30">
						<div class="tip mb-xs">
							<span>Examen de lunares</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,10);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
						<div class="tip mb-xs">
							<span>Densimetria Osea</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,9);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
						<div class="tip mb-xs">
							<span>Colonoscopia</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,16);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad > 40">
						<div class="tip mb-xs">
							<span>Examen Visual</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,29);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-6" ng-if="fSessionCI.edad > 60">
						<div class="tip mb-xs">
							<span>Chequeo Cardiologico</span>
							<button type="button" class="btn btn-success btn-sm" ng-click="btnSolicitarCita(1,2);"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="col-md-12 col-xs-12 col-sm-12" style="text-align:center;">
						Visita al especialista y solicita tu Orden Médica.
					</div>
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