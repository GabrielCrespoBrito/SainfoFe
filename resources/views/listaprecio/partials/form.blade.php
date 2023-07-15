@php

	$is_create = true;

	if( $accion == "create" ){
		$ruta = route('listaprecio.store');
	}
	else {
		$ruta = route('listaprecio.update', $listaprecio->LisCodi);
		$is_create = false;

	}

@endphp

<form action="{{ $ruta }}" method="POST">
	@csrf

	@if( ! $is_create )
		@method('PUT')
	@endif

	<div class="row">

		<div class="form-group {{ $is_create ? 'col-md-4' : 'col-md-6' }}  {{ $errors->has('LisNomb') ? 'has-error' : '' }}">

			<label> Nombre </label>
			<input type="text" name="LisNomb" placeholder="Nombre de la lista " required class="form-control" value="{{ old('LisNomb' , $listaprecio->LisNomb )  }}">		
			@if( $errors->has('LisNomb')  )
			<span class="help-block">{{ $errors->first('LisNomb') }}</span>
			@endif
		</div>


		<div class="form-group {{ $is_create ? 'col-md-4' : 'col-md-6' }} {{ $errors->has('LocCodi') ? 'has-error' : '' }}">

			<label> Local </label>
			<select {{ $is_create ? '' : 'disabled=disabled' }} name="LocCodi" id="" class="form-control">
				@foreach ( $locales as $local )
				<option {{ $listaprecio->LocCodi == $local->LocCodi ? 'selected=selected' : ''  }} value="{{ $local->LocCodi }}">{{ $local->LocNomb }}</option>						
				@endforeach
			</select>			
			@if( $errors->has('LocCodi')  )
			<span class="help-block">{{ $errors->first('LocCodi') }}</span>
			@endif
		</div>


		@if( $is_create )

    
		<div class="form-group col-md-4 {{ $errors->has('LocCodi') ? 'has-error' : '' }}">
			<label> Copiar Precios de la lista:  </label>
			<select  name="LocCodiCopy" id="" class="form-control">
				@foreach ( $listas as $lista )
				<option value="{{ $lista->LisCodi }}"> {{ $lista->LisNomb }} (Local {{ $lista->local->LocNomb }})   </option>						
				@endforeach
			</select>			
			@if( $errors->has('LocCodiCopy')  )
			<span class="help-block">{{ $errors->first('LocCodiCopy') }}</span>
			@endif
		</div>

		@endif


		<div class="col-md-12">	
			<button type="submit" class="btn btn-primary"> Guardar </button>
			<a href="{{ route('listaprecio.index') }}" class="btn btn-default pull-right">  <span class="fa fa-long-arrow-left"></span> Volver </a>
		</div>

	</div>

</form>