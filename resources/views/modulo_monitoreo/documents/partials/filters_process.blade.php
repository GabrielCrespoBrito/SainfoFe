<form id="formBuscador" style="" method="post" action="{{ route('monitoreo.empresas.process_docs_store', $empr->id) }}">  			
	@csrf

	<div class="row">
		
		@include('modulo_monitoreo.documents.partials.serie_document', ['name' => 'serie__id' , 'size' => 'col-md-3', 'series' => $empr->series,  'withLabel' => true , 'todos' => false ])

		<div class="col-md-2 form-group">
			<label for=""> Numero Inicial </label>
			<input type="number" class="form-control input-sm" name="numero_inicial">
		</div>

		<div class="col-md-2 form-group">
			<label for=""> Numero Final </label>
			<input type="number" class="form-control input-sm" name="numero_final">
		</div>

		<div class="col-md-3 form-group">
			<label for=""> Mes </label>
			@component('components.specific.select_mes');
			@endcomponent
			{{-- <input type="number" class="form-control input-sm" name="numero_final"> --}}
		</div>

		<div class="col-md-2 form-group">
			<label style="display: block; width: 100%" for="">-</label>

			<label data-toggle="tooltip" title="Si se marca reprocesar, si hay documentos que ya han tenido respuesta de la sunat, se volvera a buscar de nuevo">  <input  type="checkbox" name="reprocesar" value="1"> Reprocesar </label>
		</div> 

	</div>

	<button class="btn btn-primary btn-sm submit"> Buscar </button>
			
</form>