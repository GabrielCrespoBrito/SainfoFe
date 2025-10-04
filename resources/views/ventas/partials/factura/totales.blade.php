@php

if($create){
	$tipo_cargo_global = 0;
	$cargo_global_porc = 0;
	$cargo_global_monto = 0;
	$descuento_global_porc = 0;
	$descuento_monto = 0;
}

else {
	if($venta->VtaPPer){

		$tipo_cargo_global = (int) $venta->VtaSPer;

		if( $tipo_cargo_global == 0 || $tipo_cargo_global == 1 || $tipo_cargo_global == null ){
			$cargo_global_porc = 0;
			$cargo_global_monto = 0;
			$descuento_global_porc = $venta->VtaPPer;
			$descuento_monto = $venta->VtaDcto;		
		}
		else if( $tipo_cargo_global == 2  ){
			$cargo_global_porc = $venta->VtaPPer;
			$cargo_global_monto = $venta->VtaPerc;
			$descuento_global_porc = 0;
			$descuento_monto = 0;
		}
		else if( $tipo_cargo_global == 3  ){
			$cargo_global_porc = $venta->VtaPPer;
			$cargo_global_monto = $venta->CuenCodi['retencion'] ?? 0;
			$descuento_global_porc = 0;
			$descuento_monto = 0;
		}
	}


 	else {
		$tipo_cargo_global = 0;
		$cargo_global_porc = 0;
		$cargo_global_monto = 0;
		$descuento_global_porc = 0;
		$descuento_monto = 0;
	}

}

@endphp


<div class="totales">

<div class="row info_adicional hidden-xs">
	
	<div class="col-md-4 hidden-xs">	

		<div class="row">

		  <div class="col-md-12">  	    
				<div class="codigo">				
					<span class="hash_name"> Codigo hash </span>
					<span class="code_">{{ $create ? date('Y-m-d') : $venta->VtaFvta }}</span>
					<span class="code_">{{ $create ? date('h-i:s') : $venta->VtaHora }}</span>
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


			<div class="col-md-6 hidden-xs">
				<div class="form-group">
					<label class="col-sm-6 control-label">ISC</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" data-name="isc" default-value="0" readonly="readonly" value="{{ $create ? 0 : $venta->VtaISC }}">
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
						  <input class="form-control input-sm text-right" readonly="readonly" data-name="exoneradas" default-value="0" value="{{ $create ? 0 : $venta->VtaExon }}">
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-6 control-label"> <abbr title="Impuesto al consumo de bolsas plásticas"> ICBPER </abbr>  </label>
						<div class="col-sm-6">
							{{-- <input class="form-control input-sm text-right" data-name="total_documento" default-value="0" readonly="readonly" value="{{ $create ? 0 : $venta->VtaImpo }}"> --}}
							<input class="form-control input-sm text-right" data-name="icbper" default-value="0" readonly="readonly" value="{{ $create ? 0 : $venta->icbper }}">
							
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
					  <input class="form-control input-sm text-right" readonly="readonly" data-name="gratuitas" default-value="0" value="{{ $create ? 0 : $venta->VtaGrat }}">
					</div>
				</div>
			</div>

		{{--
			<div class="col-md-6">

				<div class="form-group">
					<label class="col-sm-6 control-label">Percepción
						<input class="text-right" type="text" style="width: 30px" name="percepcion" value="{{ $create ? 0 : $venta->VtaPPer }}" {{ $create ? '' : "disabled='disabled'" }} default-value="0"> 
					</label>

					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" data-name="percepcion" readonly="readonly" default-value="0" value="0" name="total_percepcion">
					</div>
				</div>	

				</div>
			--}}
				

			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label"> 

						Descuento 
						<input class="text-right" type="text" data-toggle="tooltip" title="Descuento Global %" style="width: 40px; height: 20px; text-align: center;" name="descuento_global" value="{{ $descuento_global_porc }}" {{ $create ? '' : "disabled='disabled'" }} default-value="0"> </label>

						<div class="col-sm-6">						
						  <input class="form-control input-sm  text-right" data-name="descuento" readonly="readonly" default-value="0" value="{{ $descuento_monto }}"> 
					</div>
				</div>
			</div>


		</div>
		<!-- /Row Columna derecha  -->

			<!-- Row Columna derecha  -->
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-sm-6 control-label control-label-tributos"> 
					<select name="tipo_cargo_global" {{ $create ? '' : 'disabled=disabled' }} class="input_tipo_cargo" id="">
						<option {{ $tipo_cargo_global == 0 ? 'selected=selected' : '' }} value=""> </option>
						<option {{ $tipo_cargo_global == 2 ? 'selected=selected' : '' }} value="percepcion"> Percepción </option>
						<option {{ $tipo_cargo_global == 3 ? 'selected=selected' : '' }} value="retencion"> Retención (%) </option>
					</select>
						
						<input class="text-right input_cargo_global" type="text" name="cargo_global" disabled='disabled' value="{{ $cargo_global_porc }}"  default-value="0"> </label>

						<!-- Dcto. Global (%)  -->
						<!-- <input class="text-right" type="text" style="width: 30px" name="descuento_global" value="{{ $create ? 0 : $venta->VtaPPer }}" {{ $create ? '' : "disabled='disabled'" }} default-value="0"> </label> -->

						<div class="col-sm-6">						

					  <input class="form-control input-sm  text-right" data-name="cargo_global" readonly="readonly" default-value="0" value="{{ $cargo_global_monto }}"> 

					  <!-- <input class="form-control input-sm  text-right" data-name="descuento" readonly="readonly" default-value="0" value="{{ $create ? 0 : $venta->VtaDcto }}">  -->


					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">

					<label class="col-sm-6 control-label no-pr">Detracción 
						<input class="text-right" type="checkbox" id="detraccionChecked" name="detraccion" value="" {{ $create ? '' : "disabled='disabled'" }}> 	
					</label>

					<div class="col-sm-6 no">						
					  {{-- <input class="form-control input-sm text-right" data-name="detraccion" readonly="readonly" value="">  --}}
					  <select {{ !$create ? 'disabled=disabled' : '' }}  class="form-control input-sm text-left" name="detraccionItem"> 
							<option value=""></option>								
							@foreach ( cacheHelper('detraccion.active')  as $detraccion )
								<option 
								@if(!$create)
									{{ $venta->VtaDetrCode == $detraccion->cod_sunat ? 'selected' : '' }}
								@endif 
                data-porc="{{ $detraccion->porcentaje }}"
								value="{{ $detraccion->cod_sunat }}">
                {{ $detraccion->descripcion_full }}

                </option>
							@endforeach
					  </select>
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
			
			<span class="" id="showFullInfo">
				 <span class="fa fa-expand"></span>
			</span>
		{{-- <div class="row">
		  <div class="form-group col-md-6 no_pr cant_div">  
		    <div class="input-group hidden">
		    	<span class="input-group-addon">Cantidad</span>
		      <input class="form-control input-sm text-right" name="cantidad_total" readonly="readonly" data-default="0" data-name="total_cantidad" value="{{ $create ? 0 : $venta->Vtacant }}" type="text">      		 
		    </div>
		  </div>
		  <div class="form-group col-md-6 no_pr no_p div_hide">  
		    <div class="input-group">
		    	<span class="input-group-addon" style="background: transparent; color: transparent;">Peso Kgs</span>
		      <input class="form-control hidden input-sm text-right" name="peso_total" readonly="readonly" data-default="0" data-name="total_peso" value="{{ $create ? 0 : 0 }}" type="text">
		    </div>
		  </div>		  
		</div> --}}
	</div>

	<div class="col-md-8 no_p">	

		<div class="table_totales">
	
			<!-- Row Columna derecha  -->
			<div class="row">
				
			<div class="col-md-6 hidden-xs">
				<div class="form-group cant_div">
					<label class="col-sm-6 control-label">Anticipo </label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" name="anticipoValue" disabled="disabled" value="{{ $create ? '0' : $venta->VtaAnticipo }}">
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group cant_div">
					<label class="col-sm-6 control-label">Importe pagar</label>
					<div class="col-sm-6">
					  <input class="form-control input-sm text-right" readonly="readonly" name="total_importe" data-name="total_importe" readonly="readonly" default-value="0" value="{{ $create ? 0 : $venta->VtaTota }}">
					</div>
				</div>
			</div>

			</div>
			<!-- /Row Columna derecha  -->

			</div>
		</div></div>
<!-- /informacion principal visible -->

</div>