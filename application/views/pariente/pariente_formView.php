<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div>
<div class="modal-body">
    <form class="" name="formPariente" novalidate>
    	<div class="row">
    		<div class="form-group mb-md col-md-4">
				<label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/"/> 
					<div class="input-group-btn ">
						<button type="button" class="btn btn-page btn-sm" ng-click="verificarDoc(); $event.preventDefault();" >CONSULTAR</button>
					</div>
				</div>
			</div>	

			<div class="form-group mb-md col-md-4" >
				<label class="block" style="margin-bottom: 4px;"> Parentesco <small class="text-danger">(*)</small> </label>
				<select class="form-control input-sm" ng-model="fData.parentesco" ng-options="item.descripcion for item in regListaParentescos" tabindex="2" required > </select>
			</div>	

    		<div class="form-group mb-md col-md-4" >
				<label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
				<select class="form-control input-sm" ng-model="fData.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="3" required > </select>
			</div>
    	</div>
    	<div class="row">
			<div class="form-group mb-md col-md-4">
				<label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.nombres" placeholder="Registre su nombre" required tabindex="4" />
			</div>
			<div class="form-group mb-md col-md-4">
				<label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="5" /> 
			</div>
			<div class="form-group mb-md col-md-4">
				<label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.apellido_materno" placeholder="Registre su apellido materno" required tabindex="6" /> 
			</div>			
		</div>		

		<div class="row">
			<div class="form-group mb-md col-md-4" >
				<label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
				<input type="email" class="form-control input-sm" ng-model="fData.email" placeholder="Registre su e-mail" required tabindex="7" />
			</div>		

			<div class="form-group mb-md col-md-4" >
				<label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
				<input type="text" class="form-control input-sm mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fData.fecha_nacimiento" required tabindex="8"/> 
			</div>
		
			<div class="form-group mb-md col-md-4">
				<label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
				<input type="tel" class="form-control input-sm" ng-model="fData.celular" placeholder="Registre su celular" ng-minlength="9" required tabindex="9" />
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
<div class="modal-footer">
    <button class="btn btn-default" ng-click="btnCancel()" tabindex="13"> SALIR </button>
    <button class="btn btn-page" ng-click="btnRegistrarPariente(); $event.preventDefault();" ng-disabled="formPariente.$invalid" tabindex="14" > GUARDAR </button>    
</div>
