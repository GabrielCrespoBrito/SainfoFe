@php
  $tipo_impresion = $tipo_impresion ?? true;
@endphp

<div class="row_pie">

	<table class="totales_cotizacion" style="margin-top: 30px">
		<tbody>
		<tr>

			<td width="40%">
				@if ($isPreventa)
				@else						
					TOTAL PESO/KGS: <span class="valor">{{ decimal( $cotizacion2->peso(),$decimals)   }}</span>
				@endif	
			</td>

			{{-- <td class="text-r"> {{ decimal($item->precioUnitario(), $decimals ) }}</td> --}}


      @if( $tipo_impresion )
			  <td width="20%">SUB TOTAL: <span class="valor">{{ decimal($cotizacion['cotbase'],$decimals) }}</span> </td>
			  <td width="20%">IGV. <span class="valor"> 18% {{ decimal($cotizacion['cotigvv'],$decimals) }} </span> </td>
      @endif

		  <td class="text-r" width="20%">TOTAL {{ $moneda_abreviatura }}: <span class="valor">{{ decimal($cotizacion['CotTota'],$decimals) }}</span> </td>

		</tr>
		</tbody>
	</table>

	<table class="pie">
		
		<!-- Condicion de venta y cuentas -->
		<tr style="font-size:.8em" class="condicion_tr">

			<td class="td_ele border-top" style="width:50%; border-right: 1px solid black">
				@if($isPreventa)
					<div style="font-size:1.3em;line-height: 2em; font-weight: bold; text-align: center" valign="bottom" class="title_c"> TOTAL A PAGAR {{ $moneda_abreviatura }}: {{ fixedValue($cotizacion['CotTota']) }} </div>
				@else 
				<div class="title_c"> CONDICIONES </div>
				@foreach( $condiciones as $condicion )
					<div>{{ $condicion }}</div>
				@endforeach
				@endif
			</td>	
					
			<td class="td_ele border-top" style="width:50%" style="border-top: none" valign="top">		
				<div class="title_c"> CUENTAS </div>
				@foreach( $cuentas as $cuenta )
					<div>  {{ $cuenta->banco->bannomb }} {{ $cuenta->moneda->monabre }} <strong>{{ $cuenta->CueNume }}</strong> </div>
				@endforeach
			</td>	

		</tr>

		<!-- Condicion de venta y cuentas -->
	</table>

	<table class="pie_nota" style="width:100%">
		<!-- Condicion de venta y cuentas -->
		<tr style="font-size: .8em" class="condicion_pie_nota">
			<td class="td_ele border-top" style="width:100%" valign="top"> 
				@if($isPreventa)	
				<p style="text-align: center">
				<strong>IMPORTANTE</strong>: Envíe su constancia de pago a 
					<strong>pagos@sainfo.pe</strong> o al WhatsApp <strong>998840052</strong> ya que es imposible identificar al depositante
				</p>
				@else
				SIN OTRO EN PARTICULAR Y ESPERANDO VERNOS FAVORECIDOS CON SUS GRATAS ORDENES, NOS SUSCRIBIMOS DE USTEDES.
				@endif
			
			</td>
		</tr>
		<!-- Condicion de venta y cuentas -->
	</table>


	@if($isPreventa)

	@else

	<br><br><br><br>


	<table class="pie_firma" style="width:100%" style="margin-top: 20px; padding-top: 20px">
		<!-- Condicion de venta y cuentas -->
		<tr style="font-size: .8em" class="condicion_pie_nota">
			<td class="td_ele" valign="top" style="width:100%; text-align: center;"> 
			<span style="padding: 10px 20px 0; border-top: 1px solid black">DPTO. VENTAS</span>
		</td>			
		</tr>
		<!-- Condicion de venta y cuentas -->
	</table>

		@endif
</div>	

{{-- @dd("asdsa") --}}