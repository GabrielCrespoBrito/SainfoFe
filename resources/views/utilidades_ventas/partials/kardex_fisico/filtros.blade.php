<div class="filtros">

	<!-- Fechas -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Fechas (desde,hasta)</legend>
				<div class="row" id="demo">
			    <div class="col-md-6">
			      <?php $date = date('Y-m-d'); ?>						
			      <input type="text" value="{{ $date }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">  
			    </div>

			    <div class="col-md-6">
			      <?php $date = date('Y-m-d'); ?>
			      <input type="text" value="{{ $date }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">  
			    </div>
				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	

	<!-- Articulos -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Articulos codigo (Inicio,Final)</legend>
				<div class="row" id="demo">
			    <div class="col-md-6">
			      <select name="articulo_desde" class="form-control input-sm flat text-center">  
						</select>
			    </div>
			    <div class="col-md-6">
						<select name="articulo_hasta" class="form-control input-sm  flat text-center">  
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
				<legend class="legendStyle">Almacen</legend>
				<div class="row" id="demo">
			    <div class="col-md-6">
			      <select type="text" value="" name="LocCodi" class="form-control input-sm flat text-center">  
							@foreach( $locales as $local )
								<option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
							@endforeach

						</select>
			    </div>

			    <div class="col-md-6">
						<div class="checkbox">
								<label>
									<input type="checkbox" name="articulo_movimiento" value="true" checked=""> Solo articulo con movimiento
								</label>
							</div>
			    </div>
				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	




</div>



