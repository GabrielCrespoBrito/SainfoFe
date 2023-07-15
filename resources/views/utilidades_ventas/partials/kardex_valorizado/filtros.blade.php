<div class="filtros">

	<!-- Fechas -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Mes</legend>
				<div class="row" id="demo">
			    <div class="col-md-12">

			    	@component('components.specific.select_mes')
			    	@endcomponent


			    </div>


				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	


	<!-- Almacen -->
	<div class="filtro">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Almacen</legend>
				<div class="row" id="demo">
			    <div class="col-md-12">
			      <select type="text" name="local" class="form-control input-sm flat text-center">  
							@foreach( $locales as $local )
								<option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
							@endforeach

						</select>
			    </div>			
				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	


	<!-- Almacen -->
	<div class="filtro">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle"></legend>
				<div class="row" id="demo">
			    <div class="col-md-6">
						<div class="checkbox">
								<label>
									<input type="radio" name="tipo" value="detalle" checked="checked"> Detalle
								</label>
							</div>			   
			    </div>

			    <div class="col-md-6">
						<div class="checkbox">
								<label>
									<input type="radio" name="tipo" value="resumen"> Resumen
								</label>
							</div>
			    </div>
				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	


</div>



