<table class="cliente" width="100%">
	
	<tr>

		<td class="data_1" width="100%">		

			<div class="cliente_tipodato"> Razón social: </div>
			<div class="cliente_dato"> {{ $cliente->PCNomb }} </div>

			<div class="cliente_tipodato"> R.U.C: </div>
			<div class="cliente_dato"> {{ $cliente->PCRucc }} </div>

			<div class="cliente_tipodato"> Dirección: </div>
			<div class="cliente_dato">{{ $cliente->PCDire }}</div>

			@if( isset($cotizacion) )
			<div class="cliente_tipodato"> Contacto: </div>
			<div class="cliente_dato">{{ $cotizacion["Cotcont"] }}</div>
			@endif

		</td>

	</tr>	

</table>


	<table class="info_pago" width="100%">
		
		<td class="data_pago" width="40%">
			<div class="cliente_tipodato"> Tipo documento: </div>
			<div class="cliente_dato"> {{ $venta2->tipo_documento->TidNomb }}</div>
		</td>


		<td class="data_pago" width="30%">
			<div class="cliente_tipodato"> Numero documento: </div>
			<div class="cliente_dato"> {{ $venta["VtaSeri"] . "-" . $venta["VtaNumee"] }}</div>
		</div>

		<td class="data_pago" width="30%">
			<div class="cliente_tipodato"> Motivo: </div>
			<div class="cliente_dato"> ANULACIÒN DE DOCUMENTO</div>
		</div>		

	</table>


<table class="cliente" width="100%">
	
	<td class="data_pago" width="25%">
		<div class="cliente_tipodato"> Codigo Resumen: </div>
		<div class="cliente_dato"> {{ $resumen->DocCEsta }} </div>
	</td>


	<td class="data_pago" width="40%">
		<div class="cliente_tipodato"> Descripción: </div>
		<div class="cliente_dato"> {{ $resumen->DocDesc }}</div>
	</div>


	<td class="data_pago" width="15%">
	  <div class="cliente_tipodato"> Fecha: </div>
	  <div class="cliente_dato"> {{ $resumen->DocFechaE }}</div>

	  </div>



	<td class="data_pago" width="20%">
		<div class="cliente_tipodato"> Ticket: </div>
		<div class="cliente_dato"> {{ $resumen->DocTicket }}</div>
	</div>

</table>


<table class="cliente" width="100%">

  <td class="data_pago" width="50%">
    <div class="cliente_tipodato"> Codigo Sunat Documento: </div>
    <div class="cliente_dato"> {{ $status_code }} </div>
  </td>


  <td class="data_pago" width="50%">
    <div class="cliente_tipodato"> Descripción: </div>
    <div class="cliente_dato"> {{ $status_message }}</div>
  </td>

</table>
