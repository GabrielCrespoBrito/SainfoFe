@include('partials.errors_html')


  <form class="reportes" method="get" action="{{ route('reportes.detraccion.pdf') }}" target="_blank">

	<!-- Fechas -->
	<div class="row">

	{{-- Mes --}}
	<div class="col-md-6 no_pr">
		<div class="filtro" style="padding-left:0" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle">Mes </legend>
					<div class="row" id="demo">
						<div class="col-md-12">
							@component('components.specific.select_mes', ['mes' => $mes ])@endcomponent
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}


	{{-- Mes --}}
	<div class="col-md-6 no_pl">
		<div class="filtro" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle"> Formato Reporte </legend>
					<div class="row" id="demo">
						{{-- Formato --}}
						<div class="col-md-12">
							<select class="form-control input-sm" name="formato">
								<option value="html" {{ $formato == 'html' ? 'selected=selected' : '' }}> EN LINEA </option>
								<option value="pdf" {{ $formato == 'pdf' ? 'selected=selected' : '' }}> PDF </option>
								<option value="excell" {{ $formato == 'excell' ? 'selected=selected' : '' }}> EXCELL </option>
							</select>
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}


	</div>		
	<!-- Fechas -->	

	
	{{-- estado_sunat --}}

  <div class="row">
    <div class="col-md-12">
      <input type="submit" name="Vista" value="Generar Reporte" class="btn btn-primary">
    </div>
  </div>

  </form>
