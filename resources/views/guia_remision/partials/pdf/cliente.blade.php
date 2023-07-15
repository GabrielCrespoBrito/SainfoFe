<table class="cliente" width="100%">	
	<tr>
		<td class="data_1" width="{{ isset($venta) ? '70%' : '10%' }}">
			<div class="border">
				<div> <strong>Razón social:</strong>  {{ $cliente->PCNomb }}</div>
				<div> <strong> R.U.C:</strong> {{ $cliente->PCRucc }}	</div>
				<div> <strong> Dirección: </strong> {{ $cliente->PCDire }} </div>
			</div>
		</td>

		@if( isset($venta) && $logo2 == false )
			<td class="data_2" width="30%">
				<div class="border">
				<p class="cliente_tipodato"> Doc interno:        </p>
				<p class="cliente_dato"> {{ $venta["VtaOper"] }} </p>
				<p class="cliente_tipodato"> Orden de compra: 	 </p>
				<p class="cliente_dato">{{ $venta["VtaPedi"] }}  </p>
				</div>
			</td>
		@endif
	</tr>	
</table>