@component('components.modal', ['id' => 'modalDocuments' , 'title' => 'Buscar documentos en la sunat de :' . $empr->razon_social ])
	@slot('body')
	
<form id="formBuscador" style="" method="post" action="{{ route('monitoreo.empresas.processDocs', $empr->id) }}">  			
			
	@csrf

			<div class="row">

				<div class="col-md-3 form-group">
					<label data-toggle="tooltip" title="Si se marca reprocesar, si hay documentos que ya han tenido respuesta de la sunat, se volvera a buscar de nuevo">  <input  type="checkbox" name="reprocesar"> Reprocesar </label>
				</div> 

				<div class="col-md-9 form-group">
					<label data-toggle="tooltip" title="Si se marca reprocesar, si hay documentos que ya han tenido respuesta de la sunat, se volvera a buscar de nuevo">  <input  type="checkbox" name="reprocesar"> Buscar número individual </label>
				</div> 

			</div>

			<div class="row">

				@include('modulo_monitoreo.documents.partials.serie_document', ['name' => 'serie__id' , 'withLabel' => true , 'todos' => false ])

				<div class="col-md-3 form-group">
					<label for=""> Numero Inicial </label>
					<input type="number" class="form-control" name="numero_inicial">
				</div>

				<div class="col-md-3 form-group">
					<label for=""> Numero Final </label>
					<input type="number" class="form-control" name="numero_final">
				</div>				
	
			</div>

			<button class="btn btn-default"> Aceptar </button>
			
	</form>
@endslot

@endcomponent 