<?php 

	$route = $create ?
	 route('unidad.store', $producto->ID ) :
	 route('unidad.update', $unidad->Unicodi );
?>

<form action="{{ $route }}" method="post">

	<p class="titulo"> {{ $create ? 'Crear unidad' : 'Modificando unidad' }} </p>

	@csrf

	<?php
		// modelo
		$m = $unidad;
		// accion
		$a = $create;

		function return_val( $accion_create , $model , $field , $v )
		{
			if( $accion_create ){	
				return old( $field , $v );
			}
			return $model->{$field};		
		}
	?>


	<div class="row">
		<div class="form-group col-md-4">
		  <label>Lista de precios</label>
			<select name="LisCodi"  class="form-control" {{ ! $create ? 'disabled' : '' }}>
			@foreach($listas->groupBy('LocCodi') as $group )
				@php

				@endphp
				<optgroup label="LOCAL: {{ optional( $group->first()->local )->LocNomb }}">
					@foreach( $group as $lista )
		  			<option {{ $lista->LisCodi == $unidad->ListCodi ? 'selected=selected' : '' }}  value="{{ $lista->LisCodi }}">{{ $lista->LisNomb }}</option>
				@endforeach

			</optgroup>
			@endforeach
		</select>
			
		</div>

		<div class="form-group col-md-offset-6 col-md-2">
		  <label> Tipo de cambio  </label>
		<input name="tipo_cambio"  class="form-control" value="{{ $tipocambio }}" disabled>
		</div>

	</div>

	<div class="row">

		<div class="form-group col-md-2">
		  <label>Cantidad</label>		  
		  <input type="text" name="UniEnte" value="{{ return_val($a,$m,'UniEnte',1) }}" required="required" class="form-control">

		</div>

		<div class="form-group col-md-4">
		  <label>Unidad</label>
		  <input readonly="readonly" class="form-control" value="{{ $producto->unpcodi }}" >
		</div>

		<div class="form-group col-md-2" {{ $errors->first('UniMedi') ? 'has-error' : '' }}>
		  <label>Cantidad</label>
		  <input class="form-control" required="required" name="UniMedi" value="{{ return_val( $a , $m , 'UniMedi', 1) }}">
		</div>

	 
		<div class="form-group col-md-2  {{ $errors->first('UniAbre') ? 'has-error' : '' }}">
		  <label>Medida</label>
		  <select name="UniAbre"  class="form-control">
		  @foreach($unidades as $uni)
		  	<option  {{ $uni->UnPCodi == $unidad->UniAbre ? 'selected=selected' : '' }}  value="{{ $uni->UnPCodi }}">{{ $uni->UnPCodi }} - {{ $uni->UnPNomb }}</option>
		  @endforeach
		  </select>
		</div>


		<div class="form-group col-md-2  {{ $errors->first('UniPeso') ? 'has-error' : '' }}">
		  <label>Peso</label>
		  <input 		  
		  type="text" 
		  required="required" 
		  name="UniPeso" 
		  value="{{ return_val( $a , $m , 'UniPeso', $producto->ProPeso  ) }}" 
		  class="form-control">
		</div>

	</div>

	<div class="row">

		<div class="form-group col-md-2  {{ $errors->first('UniPUCD') ? 'has-error' : '' }}">
		  <label>Costo USD$</label>
		  <input 		  
		  type="text" 
		  required="required" 
		  name="UniPUCD" 
		  value="{{ return_val( $a , $m , 'UniPUCD', $producto->ProPUCD  ) }}" 
		  class="form-control">
		</div>


		<div class="form-group col-md-2  {{ $errors->first('UniPUCS') ? 'has-error' : '' }}">
		  <label>Costo S./</label>

		  <input 		  
		  type="text" 
		  required="required" 
		  name="UniPUCS" 
		  value="{{ return_val( $a , $m , 'UniPUCS', $producto->ProPUCS  ) }}" 
		  class="form-control">
		</div>



		<div class="form-group col-md-2  {{ $errors->first('UniMarg') ? 'has-error' : '' }}">
		  <label> % Margen/</label>

		  <input 		  
		  type="text" 
		  required="required" 
		  name="UniMarg" 
		  value="{{ return_val( $a , $m , 'UniMarg', $producto->ProMarg  ) }}" 
		  class="form-control">
		</div>


		<div class="form-group col-md-2  {{ $errors->first('UNIPUVD') ? 'has-error' : '' }}">
		  <label> Venta USD$ </label>
		  <input 		  
		  type="text" 
		  required="required" 
		  name="UniPUVD" 
		  data-copy="{{ $producto->ProPUVD }}" 
		  value="{{ return_val( $a , $m , 'UNIPUVD', $producto->ProPUVD  ) }}" 
		  class="form-control">
		</div>
		{{-- UniPUVD --}}
{{-- UNIPUVD --}}


		<div class="form-group col-md-2  {{ $errors->first('UNIPUVS') ? 'has-error' : '' }}">
		  <label> Venta S/</label>
		  <input 		  
		  type="text" 
		  required="required" 
		  name="UNIPUVS" 
		  value="{{ return_val( $a , $m , 'UNIPUVS', $producto->ProPUVS  ) }}" 
		  class="form-control">
		</div>

		<div class="form-group col-md-2  {{ $errors->first('UniPAdi') ? 'has-error' : '' }}">
		  <label> Precio Adic</label>
	  <input 		  
		  type="text" 
		  required="required" 
		  name="UniPAdi" 
		  value="{{ return_val( $a , $m , 'UniPAdi', 0 ) }}" 
		  class="form-control">
		</div>

	</div>

	<div class="row">

		<div class="col-md-12">
			<button type="submit" class="btn btn-success btn-flat"> Guardar </button>
		</div>

	</div>

</form>