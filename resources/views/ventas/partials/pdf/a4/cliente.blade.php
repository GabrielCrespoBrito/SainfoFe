<table class="cliente" width="100%" style="margin-bottom: 5px">
		<tr>

		<td class="data_1" width="50%">		

			<div class="border">

				<div> <span class="cliente_tipodato"> Razón social:  </span> 
					<span class="cliente_dato"> {{ $cliente->PCNomb }} </span>
				</div>

				<div> <span class="cliente_tipodato"> {{ $cliente->tipo_documento->TdocNomb }} </span>
					<span class="cliente_dato"> {{ $cliente->PCRucc }} </span>
				</div>

				<div> <span class="cliente_tipodato"> Dirección: </span>
					<span class="cliente_dato">{{ $cliente->PCDire }}</span>
				</div>

				@if( isset($cotizacion) )


					<div> <span class="cliente_tipodato"> Contacto: </span>
						<span class="cliente_dato">{{ $cotizacion["Cotcont"] }}</span>
					</div>

					@if( $showVendedor )

					<div> <span class="cliente_tipodato"> Vendedor: </span>
						<span class="cliente_dato">{{ $cotizacion2->vendedor_()->vennomb }}</span>
					</div>

					@endif

								
				@endif



			</div>

		</td>

		@if($isPreventa)
		<td class="data_1" width="50%">		

			<div class="border">

				<div> <span class="cliente_tipodato"> Fecha emisión:  </span> 
					<span class="cliente_dato"> {{ $cotizacion2->CotFVta }} </span>
				</div>

				<div> <span class="cliente_tipodato"> Fecha vencimiento: </span>
					<span class="cliente_dato"> {{ $cotizacion2->CotFVen }} </span>
				</div>

				<div> <span class="cliente_tipodato"> Moneda: </span>
					<span class="cliente_dato">{{ $moneda_nombre }}</span>
				</div>
			</div>

		</td>		
		@endif

	</tr>	


		<td class="data_1 observacion" {{ $isPreventa ? 'colspan=2' : '' }} width="100%">		
			<div class="border">
				<div> 
					<span class="cliente_tipodato"> Observación: </span>
					@if( isset($cotizacion) )					
					<span class="cliente_dato"> {{ $cotizacion2->cotobse  }} </span>
					@else
					<span class="cliente_dato"> {{ isset($venta['VtaObse']) ? $venta['VtaObse'] : '-'  }} </span>
					@endif
				</div>
			</div>
		</td>

</table>

@if( isset($venta) )

	@php

		$hasGuia = isset($venta["GuiOper"]);
		$hasResponsable = isset($venta["User_Crea"]);

		$guiaTitulo = "-";	
		$guiaNombre = "-";

		$responsableTitulo = "-";	
		$responsableNombre = "-";

		if( $hasGuia ){ 
			$guiaTitulo = $venta["GuiOper"] ? "Guia N°" : '-'; 		
		}

		if( $hasResponsable ){ 
			$responsableTitulo = $venta["User_Crea"] ? "Responsable:" : '-'; 		
			$responsableNombre = $venta["User_Crea"] ? $venta["User_Crea"] : '-'; 
		}	


	@endphp


	<div class="border-radius">
		<table class="info_pago" width="100%">
			
			<td class="data_pago no-border-left" width="15%">
				<div class="cliente_tipodato"> Fecha: </div>
				<div class="cliente_dato"> {{ $venta["VtaFvta"] }} </div>
			</td>

			<td class="data_pago" width="15%">
				<div class="cliente_tipodato"> Forma de pago: </div>
				<div class="cliente_dato"> {{ $forma_pago->connomb }} </div>
			</td>

			<td class="data_pago" width="15%">
				<div class="cliente_tipodato"> Vendedor: </div>
				<div class="cliente_dato"> {{ $venta["Vencodi"] }} </div>
			</td>

			<td class="data_pago" width="15%">
				<div class="cliente_tipodato"> Guia </div>
				<div class="cliente_dato"> 
					@foreach( $guias as $guia )
					<span style="font-size:.8em"> {{ $guia }} </span> <br>  
					@endforeach
			</div>
			</td>

			<td class="data_pago" width="20%">
				<div class="cliente_tipodato"> {{ $responsableTitulo }} </div>
				<div class="cliente_dato"> {{ $responsableNombre }} </div>
			</td>


			<td class="data_pago no-border-right" width="20%">
				<div class="cliente_tipodato"> Orden de Compra </div>
				<div class="cliente_dato"> {{ isset($venta["VtaPedi"]) ? $venta["VtaPedi"] : ''  }}	  </div>
			</td>

		</table>
	</div>

@endif
