@php
  $fechas = fechas_reporte();

@endphp

<!-- Articulo -->
<div class="filtro" id="condicion">
	<div class="cold-md-12">
		<fieldset class="fsStyle">			
			
			<legend class="legendStyle">
				@if(!isset($onlyOne))
					Fechas (desde,hasta)
					@else
					Fecha
				@endif				
			</legend>

			<div class="row" id="demo">

		    <div class=" {{ isset($onlyOne) ? 'col-md-12' : 'col-md-6' }}">
		      <input type="text" value="{{ $fechas->inicio }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">  

		    </div>
		    @if( !isset($onlyOne) )
		    <div class="col-md-6">
		      <input type="text" value="{{ $fechas->final }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">  
		    </div>
		    @endif
			
			</div>									

	  </fieldset>
	</div>
</div>
<!-- Articulo -->	
