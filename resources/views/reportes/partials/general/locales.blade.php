@php
  $empresa = get_empresa();
  $locales  = $empresa->almacenes;
  $conVendedores  = $conVendedores ?? false;
  $grupos = $grupos ?? App\Grupo::all();
@endphp

<!-- Almacen -->
<div class="filtro">
	<div class="cold-md-12">
		<fieldset class="fsStyle">			
			<legend class="legendStyle">Grupo - Almacen {{ $conVendedores ? '- Vendedor' : ''  }} </legend>
			<div class="row" id="demo">

		    <div class="{{ $conVendedores ? 'col-md-4' : 'col-md-6' }}">
		      <select type="text" name="grupos" class="form-control input-sm flat text-center">  
						<option value="todos"> -- TODOS -- </option>						
						@foreach( $grupos as $grupo )
							<option value="{{ $grupo->GruCodi }}"> {{ $grupo->GruNomb }} </option>
						@endforeach
					</select>
		    </div>		

        <div class="{{ $conVendedores ? 'col-md-4' : 'col-md-6' }}">
		      <select type="text" name="local" class="form-control input-sm flat text-center">  
						<option value="todos"> -- TODOS -- </option>						
						@foreach( $locales as $local )
							<option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
						@endforeach
					</select>
		    </div>


        @if( $conVendedores )
        @php
          $vendedores = $empresa->vendedores;
        @endphp

        <div class="{{ $conVendedores ? 'col-md-4' : 'col-md-6' }}">
		      <select type="text" name="vendedor" class="form-control input-sm flat text-center">  
						<option value="todos"> -- TODOS -- </option>						
						@foreach( $vendedores as $vendedor )
							<option value="{{ $vendedor->Vencodi }}"> {{ $vendedor->vennomb }} </option>
						@endforeach
					</select>
		    </div>
        @endif

			</div>									
	  </fieldset>
	</div>
</div>
<!-- Fechas -->	