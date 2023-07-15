@php
    $empresa = get_empresa();
    $locales  = $empresa->almacenes;
@endphp

<!-- Almacen -->
<div class="filtro">
	<div class="cold-md-12">
		<fieldset class="fsStyle">			
			<legend class="legendStyle">Almacen</legend>
			<div class="row" id="demo">
		    <div class="col-md-12">
		      <select type="text" name="local" class="form-control input-sm flat text-center">  
						@foreach( $locales as $local )
							<option value="todos"> TODOS </option>						
							<option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
						@endforeach

					</select>
		    </div>			
			</div>									
	  </fieldset>
	</div>
</div>
<!-- Fechas -->	