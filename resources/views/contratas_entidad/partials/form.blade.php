	@php 
		$is_show = $accion == "show"; 
		$is_create = $accion == "create"; 
		$is_edit = $accion == "edit"; 
	@endphp

	<div class="row">

		<div class="form-group col-md-6">
			<label> Tipo </label>
	    <select id="entidad_tipo" {{ !$is_create  ? 'disabled=disabled' : ''  }} name="entidad_tipo" class="form-control input-sm">
	    	<option {{ $contrata_entidad->entidad_type === "App\ClienteProveedor" ? 'selected' : '' }} value="App\ClienteProveedor"> Cliente </option>
	    </select>
		</div>

		<div class="form-group col-md-6">
			<label> - </label>
			@if( !$is_create )
				<input type="" class="form-control input-sm" value="{{ $contrata_entidad->nameEntidad()  }}" disabled="disabled" >
			@else

		    <select id="entidad_id" name="entidad_id" data-id="{{ $contrata_entidad->entidad_id }}" data-text="{{ $contrata_entidad->entidad_id }}" class="form-control input-sm select2" data-url="{{ route('clientes.buscar_cliente_select2') }}">	    	
		    </select>

			@endif



		</div>

	</div>

	<div class="row">

		<div class="form-group col-md-12">

			<label> Documento </label>
			
			@if( !$is_create )
				<input type="" class="form-control input-sm" value="{{ $contrata_entidad->documentoName()  }}" disabled="disabled" >
			@else 

	    <select id="documento_id" {{ $is_show ? 'disabled=disabled' : '' }} name="documento_id" class="form-control input-sm"">
	    	@foreach( $documentos as $documento  )
	    		<option {{ $contrata_entidad->contrata_id == $documento->id ? 'selected' : '' }} value="{{ $documento->id }}"> {{ $documento->nombre }} </option>
	    	@endforeach
	    </select>
	    @endif
		</div>

	</div>


	<div class="row">

		<div class="form-group col-md-4">
			<label> Fecha emisión </label>						
			<input type="text" {{ $is_create ? '' : 'disabled=disabled' }} name="fecha_emision" class="form-control input-sm datepicker"  value="{{ $contrata_entidad->fecha_emision ?? date('Y-m-d') }}">
		</div>

		<div class="form-group col-md-4">
			<label> Fecha inicio </label>						
			<input type="text" {{ $is_create ? '' : 'disabled=disabled' }} name="fecha_inicio" class="form-control input-sm datepicker" value="{{ $contrata_entidad->fecha_inicio ?? date('Y-m-d')  }}">
		</div>

		<div class="form-group col-md-4">
			<label> Fecha final </label>						
			<input type="text" {{ $is_create ? '' : 'disabled=disabled' }} name="fecha_final" class="form-control input-sm datepicker" value="
			{{ 
				$contrata_entidad->fecha_emision ?? 
				date('Y-m-d' , strtotime(date('Y-m-d') . " +1 year")) 
			}}

			">
		</div>		

	</div>


		<div class="row">

		@if( !$is_create )
		<div class="form-group col-md-12">
  		<a target="_blank" class="btn btn-default btn-xs" href="{{ route('contratas_entidad.pdf', $contrata_entidad->id) }}"> <span class="fa fa-file-pdf-o"></span>  Ver pdf </a>
		</div>
		@endif

	</div>

	@if( $is_create )
		@method('POST')
	@else
		@method('PUT')
	@endif

	@if( $is_edit )	


	<main>
		<div class="document-editor">
			<div class="toolbar-container"></div>
			<div class="content-container">
				<div id="contenido">
					{!! $contrata_entidad->contenido !!}
				</div>
			</div>
		</div>
	</main>


{{-- 	<form style="margin-top: 30px;">
		<textarea id="contenido" name="contenido" rows="10" cols="80">
  		{!! $contrata_entidad->contenido !!}
		</textarea>
	</form> --}}

  	
  @elseif( $is_show )

  	<div class="contenido-documento">
  		{!! $contrata_entidad->contenido !!}
  	</div>

  @endif


	<div class="form-group" style="margin-top:20px">

	@php	
		$url = $is_create ? 
		route('contratas_entidad.store') : 
		route('contratas_entidad.update', $contrata_entidad->id);				

		$name = $is_create ? "Guardar" : "Actualizar";
	@endphp

  @if( $is_create || $is_edit )
		<a href="#" id="crear" class="btn btn-success" data-url="{{ $url }}" > {{ $name }} </a>
	@else 

		<a href="{{ route('contratas_entidad.edit', $contrata_entidad->id) }}"  class="btn btn-success"> Modificar </a>	

	@endif
		<a href="{{ route('contratas_entidad.index') }}" class="btn btn-danger"> Volver </a>
	</div>

	
