@include('partials.errors_html')


  <form class="reportes" method="get" action="{{ route('reportes.inventario_valorizado.pdf') }}" target="_blank">

	<!-- Fechas -->
	<div class="row">

	{{-- Mes --}}
	<div class="col-md-4 no_pr no_pl">
		<div class="filtro" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle"> Almacen </legend>
					<div class="row" id="demo">
						{{-- Formato --}}
						<div class="col-md-12">	
						<select class="form-control input-sm" name="local">
							@foreach( $locales as $local )
								<option value="{{ $local->LocCodi }}" {{ $loccodi == $local->LocCodi ? 'selected=selected' : '' }}> {{ $local->LocNomb }} </option>
							@endforeach
							</select>
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}

	{{-- Mes --}}
	<div class="col-md-4 no_pr">
		<div class="filtro" style="padding-left:0" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle">Moneda </legend>
					<div class="row" id="demo">
						<div class="col-md-12">
						<select class="form-control input-sm" name="moneda">
								<option value="01" {{ $moncodi == '01' ? 'selected=selected' : '' }}> SOLES </option>
								<option value="02" {{ $moncodi == '02' ? 'selected=selected' : '' }}> DOLARES </option>
							</select>							
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}

	{{-- Mes --}}
	<div class="col-md-4 no_pr">
		<div class="filtro" style="padding-left:0" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle"> Tipo de existencia </legend>
					<div class="row" id="demo">
						<div class="col-md-12">
						<select class="form-control input-sm" name="tipo_existencia">
							<option value=""> - TODOS -  </option>
							@foreach( $tipos_existencia as $tipo_existencia )
								<option value="{{ $tipo_existencia->TieCodi }}" {{ $tipo_existencia_id == $tipo_existencia->TieCodi ? 'selected=selected' : '' }}> {{ $tipo_existencia->TieNomb }} </option>
							@endforeach
							</select>						
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}

	</div>		
	<!-- Fechas -->	

	
	<!-- Fechas -->
	<div class="row">

	{{-- Mes --}}
	<div class="col-md-12 no_pr no_pl">
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
