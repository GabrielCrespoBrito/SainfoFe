<table class="t_cabezera" width="100%">
	<tr>

		<!-- data 1 (logo,nombre de empresa)-->
		<td class="data_1">
			<table width="100%">
				<tr>
					<td class="data_1_1 text-c">
					    <img class="img_logo" src="data:image/png;base64,{{ $logo }}">
					</td>

					<td class="data_1_2">
						<p class="empresa_nombre text-c"> 
							@if($logo2)
					    	<img class="img_logo2" height="30px" src="data:image/png;base64,{{ $logo2 }}">
							@else 
								{{ $empresa['EmpNomb'] }}
							@endif
						</p>

							@if(!$logo2)					
								<p class="empresa_nombre_subtitulo"> {{ $empresa['EmpLin5'] }} </p>						
							@endif
					</td>

				</tr>
				<tr>
					<td colspan="2" class="info_empresa">
							<p class="direccion">{{ $empresa['EmpLin2'] }}</p>
							<p class="telefono"> {{ $empresa['EmpLin4'] }}</p>
							<p class="email">{{ $empresa['EmpLin3'] }}</p><p class="email">{{ $empresa['EmpLin6'] }}</p>		
					</td>
				</tr>
			</table>
		</td>

		<!-- data 2 (ruc, numero factura)-->
		<td class="data_2">
			<p class="empresa_ruc"> R.U.C.N: <span class="ruc"> {{ $empresa['EmpLin1'] }} </span> </p>
			<p class="factura_titulo"> {{ $nombre_documento }} </p>
			<p class="factura_numero"> N° <span class="factura_serie"> {{ $documento_id }} </span> </p>		
		</td>
	</tr>	

</table>


<table class="cliente" width="100%">
	
	
	<td class="data_pago" width="35%">
		<div class="cliente_tipodato"> Codigo Sunat: </div>
		<div class="cliente_dato"> {{ $resumen->DocCEsta }} </div>
	</td>


	<td class="data_pago" width="65%">
		<div class="cliente_tipodato"> Descripción: </div>
		<div class="cliente_dato"> {{ $resumen->DocDesc }}</div>
	</div>


</table>