<table class="cliente" width="100%">
	
	
	<td class="data_pago" width="35%">
		<div class="cliente_tipodato"> Tipo documento: </div>
		<div class="cliente_dato"> Boletas </div>
	</td>


	<td class="data_pago" width="65%">
		<div class="cliente_tipodato"> Numero de documentos: </div>
		<div class="cliente_dato"> {{ $documentos_name }}</div>
	</div>


</table>


<table class="cliente" width="100%">
	
	
	<td class="data_pago" width="25%">
		<div class="cliente_tipodato"> Codigo Sunat: </div>
		<div class="cliente_dato"> {{ $resumen->DocCEsta }} </div>
	</td>


	<td class="data_pago" width="55%">
		<div class="cliente_tipodato"> Descripción: </div>
		<div class="cliente_dato"> {{ $resumen->DocDesc }}</div>
	</div>

	<td class="data_pago" width="20%">
		<div class="cliente_tipodato"> Ticket: </div>
		<div class="cliente_dato"> {{ $resumen->DocTicket }}</div>
	</div>


</table>
