<div class="totales">

<div class="row info_adicional">
	
	<div class="col-md-4">	
		
		<div class="row">
			<div class="col-md-12">
				<p class="cifra_cantidad" data-change_cantidad="{{ $create }}">{{ $create ? "CERO SOLES" : "" }}</p>
			</div>
		</div>
	</div>

	<div class="col-md-8 no_p">	

		<div class="table_totales">

			<div class="row">

			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label"> ICBPER </label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="icbper" default-value="0" value="{{ $create ? 0 : $cotizacion->icbper }}">
					</div>
				</div>
			</div>

			</div>

			<!-- Row Columna derecha  -->
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-6 control-label">IGV</label>
						<div class="col-sm-6">
						  <input class="form-control input-sm text-right" readonly="readonly" data-name="igv" default-value="0" value="{{ $create ? 0 : $cotizacion->cotigvv }}">
						</div>
					</div>
				</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Descuento</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" default-value="0" data-name="descuento" value="{{ $create ? 0 : $cotizacion->cotdcto }}">
					</div>
				</div>				
			</div>


			</div>
			<!-- /Row Columna derecha  -->

			<!-- Row Columna derecha  -->
			<div class="row">

			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">ISC</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="isc" default-value="0" value="{{ $create ? 0 : $cotizacion->cotisc }}">

					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Sub total</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="subtotal" default-value="0" value="{{ $create ? 0 : $cotizacion->cotbase }}">
					</div>
				</div>
			</div>



			</div>

			</div>
		</div>
	</div>


<!-- informacion principal visible -->
	<div class="row info_principal">

		<div class="col-md-4">			
		<div class="row">
		  <div class="form-group col-md-6 no_pr cant_div">  
		    <div class="input-group">
		    	<span class="input-group-addon">Cantidad</span>
		      <input class="form-control input-sm text-right" name="cantidad_total" readonly="readonly" data-default="0" data-name="total_cantidad" value="{{ $create ? "0" : $cotizacion->cotcant }}" type="text">      		 
		    </div>
		  </div>
		  <div class="form-group col-md-6no_pr no_p div_hide">  
		    <div class="input-group">
		    	<span class="input-group-addon">Peso Kgs</span>
		      <input class="form-control input-sm text-right" name="peso_total" readonly="readonly" data-default="0" data-name="total_peso" value="{{ $create ? "0.00"  : fixedValue($cotizacion->peso()) }}" type="text">
		    </div>
		  </div>		  
		</div></div>
	<div class="col-md-8 no_p">	

		<div class="table_totales">
	
			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group div_hide">
					<label class="col-sm-6 control-label">Estado</label>
					<div class="col-sm-6">
					  <p class="form-control estado input-sm text-right">PENDIENTE</p>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group cant_div">
					<label class="col-sm-6 control-label">Total</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" name="total_importe" data-name="total_importe" readonly="readonly" default-value="0" value="{{ $create ? 0 : fixedValue($cotizacion->cotimpo) }}">
					</div>
				</div>
			</div>

			</div>
			<!-- /Row Columna derecha  -->

			</div>
		</div></div>
<!-- /informacion principal visible -->

</div>



</div>