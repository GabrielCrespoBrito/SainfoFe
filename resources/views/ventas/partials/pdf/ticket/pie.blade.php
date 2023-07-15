<div class="pie">

	<div class="cifra_letra">SON: {{ $cifra_letra }}</div>
	
	<p class="border-separador"></p>

	<div class="forma_pago">
		<p><span>FORMA DE PAGO: </span> <span> {{ $forma_pago->connomb }} </span> </p>
    @if( $venta2->hasPlaca() )
      <p><span>PLACA: </span> <span> {{ $venta2->getPlaca() }} </span> </p>
    @endif

		@if( $venta2->isCredito() )
			@php
				$creditos = $venta2->getCreditos();
				$fp = $venta2->forma_pago;
			@endphp
			<table width="100%">
				<thead>
					<tr style="text-align: left;">
						<td width="30%" style="text-align:center"> N° Cuota </td>
						<td width="40%" style="text-align:center"> Fec. Venc. </td>
						<td width="40%" class="text-align:left"> Monto </td>
					</tr>
				</thead>
				<tbody>

				@foreach( $creditos as $credito )
				<tr style="text-align: left;">
					<td style="text-align:center"> {{ (int) $credito->item }} </td>
					<td style="text-align:center"> {{ $credito->fecha_pago }} </td>
					<td style="text-align: left;"> {{ $credito->getMonedaAbreviatura() }} {{ $credito->monto }} </td>
				</tr>
				@endforeach
				</tbody>
			</table>
		@endif

		<!--  -->
		
	</div>

	<p class="border-separador"></p>


	<div class="codigo_barra" style="text-align:center">
		<img width="100px" height="100px" style="text-align: center;" src="data:image/png;base64, {!! base64_encode($qr)!!} ">

	</div>

	<p class="border-separador"></p>

	<div class="letra_peq">
		<p> Representación impresa de la {{$venta['nombre_documento']}}</p>
		<p> Este documento puede ser consultado en: {{ config('app.url_busqueda_documentos') }} </p>

	  <p class="center letra_peq">*** GRACIAS POR SU COMPRA ***</p>
	</div>

</div> 
<!-- /items 