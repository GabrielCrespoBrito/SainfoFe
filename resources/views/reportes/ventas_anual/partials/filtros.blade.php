<form action="{{ route('reportes.ventas_anual.pdf') }}" method="get">
@csrf

<div class="filtros">

	<!-- Articulo -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Año </legend>

				<div class="row" id="demo">

			    <div class="col-md-12">
			      <select name="year" class="form-control input-sm no_br flat text-center">	

			      @php
			      	$periodos = get_empresa()->periodos;
			      @endphp

			      @foreach( $periodos as $periodo )  

			      	<option data-link="{{ route('reportes.ventas_anual.pdf', $periodo->Pan_cAnio) }}" value="{{ $periodo->Pan_cAnio }}"> {{ $periodo->Pan_cAnio }} </option>

			      @endforeach


			    </select>

			    </div>
				
				</div>									

		  </fieldset>
		</div>
	</div>
	<!-- Articulo -->	

</div>

<div>

<button type="submit" class="btn btn-flat btn-primary"> Reporte </button>

</div>

</form>