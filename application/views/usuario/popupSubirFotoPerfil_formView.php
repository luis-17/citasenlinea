<div class="modal-header">
	<h4 class="modal-title"> {{ titleFormDet }}  </h4>
</div>
<div class="modal-body">
    <form class="row" name="formSubirFoto" novalidate> 
		<div class="form-group mb-md col-sm-12">
			<label class="control-label mb-xs"> Seleccione archivo a subir (Peso Máximo: 1MB)</label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
				<div>
					<a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file btn-sm" ng-hide="fDataSubida.archivo">
						<span class="fileinput-new">Seleccionar archivo</span>
						<input type="file" name="file" file-model="fDataSubida.archivo" /> 
					</span>					
				</div>
			</div>
		</div>
	</form>
	<alert type="{{fAlertSubida.type}}" close="fAlertSubida = null" ng-show='fAlertSubida.type' class="p-sm">
        <strong> {{ fAlertSubida.strStrong }} <i class='{{fAlertSubida.icon}}'></i></strong> 
        <span ng-bind-html="fAlertSubida.msg"> </span>
    </alert>
</div>
<div class="modal-footer">
    <button class="btn btn-default" ng-click="cancelSubida()">SALIR</button>
    <button class="btn btn-page" ng-click="aceptarSubida(); $event.preventDefault();" ng-disabled="formSubirFoto.$invalid">SUBIR</button>    
</div>