<div class="content container" ng-controller="inicioController">       
      <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
          <div class="col-md-12 col-xs-12 col-sm-12 dashboard">
              <div class="row">
	              <div class="col-md-12 col-xs-12 col-sm-12 ">
	              	<div class="saludo"> ¡BIENVENIDO {{fSessionCI.nombres}} {{fSessionCI.apellido_paterno}}! </div>
	              </div>
	              <div class="col-md-8 col-md-offset-2 col-xs-12 col-sm-12">
	              	<div class="row valores">
	              		<div class="item">
	              			<div class="imagen">
	              				<img src="{{ dirImages + 'dashboard/icon-peso.png' }}"  />
	              			</div>
	              			<div class="value">
	              				<span class="title">Peso</span>
	              				<div class="data">{{fSessionCI.peso}} <span class="medida" >Kg.</span></div>
	              			</div>
	              		</div>
	              		<div class="item">
	              			<div class="imagen">
	              				<img src="{{ dirImages + 'dashboard/icon-estatura.png' }}"  />
	              			</div>
	              			<div class="value">
	              				<span class="title">Estatura</span>
	              				<div class="data">{{fSessionCI.estatura}} <span class="medida" >Mt.</span></div>
	              			</div>
	              		</div>
	              		<div class="item">
	              			<div class="imagen">
	              				<img src="{{ dirImages + 'dashboard/icon-tipo-sangre.png' }}"  />
	              			</div>
	              			<div class="value">
	              				<span class="title">Tipo de Sangre</span>
	              				<div class="data">{{fSessionCI.tipo_sangre.descripcion}}</div>
	              			</div>
	              		</div>
	              		<div class="item">
	              			<div class="imagen">
	              				<img src="{{ dirImages + 'dashboard/icon-sexo-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
	              			</div>
	              			<div class="value">
	              				<span class="title">Sexo</span>
	              				<div class="data">{{fSessionCI.sexo}}</div>
	              			</div>
	              		</div>
	              		<div class="item">
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
	              <div class="col-md-12 col-xs-12 col-sm-12 ">
	              	<div class="div-btn">
	              		<div class="btn-go-perfil" ng-click="goToPerfil();">VER MI PERFIL <i class="fa fa-angle-right"></i></div>
	              	</div>
	              </div>
	              <div class="col-md-12 col-xs-12 col-sm-12 ">
	              	<div class="col-md-offset-2 col-md-4 col-xs-12 col-sm-12 ">
	              		<div class="caja">
	              			
	              		</div>
	              	</div>
	              	<div class="col-md-4 col-md-offset-right-2 col-xs-12 col-sm-12 ">
	              		<div class="caja">
	              			
	              		</div>
	              	</div>
	              	<!-- <div class="col-md-offset-2 col-md-4 col-xs-12 col-sm-12 ">
	              		<div class="caja">
	              			
	              		</div>
	              	</div>
	              	<div class="col-md-4 col-md-offset-right-2 col-xs-12 col-sm-12 ">
	              		<div class="caja">
	              			
	              		</div>
	              	</div> -->
	              </div>	              
              </div>
          </div>
      </div>
</div>