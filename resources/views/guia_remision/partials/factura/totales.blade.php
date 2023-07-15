@php
	if(isset($guia)){
		$fecha = $guia->GuiFemi; 
		$hora = date('h-i:s');
		$peso = $guia->guiporp;
		$cantidad = $guia->guicant;
	}
	else {
		$fecha =  date('Y-m-d');
		$hora = date('h-i:s');
		$peso = 0;
		$cantidad = 0;
	}




@endphp

<div class="totales">

<div class="row info_adicional">
	
	<div class="col-md-4">	

		<div class="row">

		  <div class="col-md-12">  	    
				<div class="codigo">				
					<span class="hash_name"> Codigo hash </span>
					<span class="code_">{{ $fecha }}</span>
					<span class="code_">{{ $hora }}</span>
				</div>	
		  </div>

	  </div>


		<div class="row">
			<div class="col-md-12">
				<p class="cifra_cantidad" data-change_cantidad="{{ $create }}">{{ $create ? "CERO SOLES" : $venta->cifra_letra() }}</p>
			</div>
		</div>

	</div>

	<div class="col-md-8 no_p">	

		<div class="table_totales">
			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Opr. Gravadas</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" data-name="gravadas" default-value="0"  readonly="readonly" value="{{ $create ? 0 : $venta->Vtabase }}">
					</div>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">ISC</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" data-name="isc" default-value="0" readonly="readonly" value="0">
					</div>
				</div>				
				</div>

				</div>
			<!-- /Row Columna derecha  -->


			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Opr. Inafecta</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="inafectas" default-value="0" value="{{ $create ? 0 : $venta->VtaInaf }}">
					</div>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">IGV</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="igv" default-value="0" value="{{ $create ? 0 : $venta->VtaIGVV }}">
					</div>
				</div>				
				</div>

				</div>
			<!-- /Row Columna derecha  -->



			<!-- Row Columna derecha  -->
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-6 control-label">Opr. Exoneradas</label>
						<div class="col-sm-6">
						  <input class="form-control input-sm text-right" readonly="readonly" data-name="exoneradas" default-value="0" value="{{ $create ? 0 : $venta->totalExonerada() }}">
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-6 control-label">Total Dct</label>
						<div class="col-sm-6">
						  <input class="form-control input-sm text-right" data-name="total_documento" default-value="0" readonly="readonly" value="{{ $create ? 0 : $venta->VtaImpo }}">
						</div>
					</div>				
					</div>
				</div>
			<!-- /Row Columna derecha  -->

			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Opr. Gratuitas</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="gratuitas" default-value="0" value="{{ $create ? 0 : $venta->totalGratuita() }}">
					</div>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Percepción</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" default-value="0" data-name="percepcion" value="0">
					</div>
				</div>				
				</div>

				</div>
			<!-- /Row Columna derecha  -->

			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label">Descuento 
						<input class="text-right" type="text" style="width: 30px" name="descuento_global" value="{{ $create ? 0 : $venta->VtaDcto }}" {{ $create ? '' : "disabled='disabled'" }} default-value="0"> </label>

					<div class="col-sm-6">						
					  <input class="form-control input-sm text-right" data-name="descuento" readonly="readonly" default-value="0" value="{{ $create ? 0 : $venta->Dcto }}"> 
					</div>
				</div>
			</div>
			</div>
			<!-- /Row Columna derecha  -->

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
		      <input class="form-control input-sm text-right" name="cantidad_total" readonly="readonly" data-default="0" data-name="total_cantidad" value="{{ $cantidad }}" type="text">      		 
		    </div>
		  </div>
		  <div class="form-group col-md-6no_pr no_p div_hide">  
		    <div class="input-group">
		    	<span class="input-group-addon">Peso Kgs</span>
		      <input class="form-control input-sm text-right" name="peso_total" readonly="readonly" data-default="0" data-name="total_peso" value="{{ $peso }}" type="text">
		    </div>
		  </div>		  
		</div></div>
	<div class="col-md-8 no_p">	

		<div class="table_totales">
	
			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group div_hide">
					<label class="col-sm-6 control-label">Pagado</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" default-value="0" value="{{ $create ? 0 : $venta->VtaPago }}">
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group cant_div">
					<label class="col-sm-6 control-label">Importe pagar</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" name="total_importe" data-name="total_importe" readonly="readonly" default-value="0" value="{{ $create ? 0 : $venta->VtaImpo }}">
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