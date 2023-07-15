<table class="t_cabezera" width="100%">
	<tr>
		<!-- data 1 (logo,nombre de empresa)-->
		<td class="data_1" style="width: 70% !important">
			<table width="100%">
				<tr>
					@if( $onlyShowLogo2  )
						<td width="100%" class="text-c">
					    <img class="showAll" src="data:image/png;base64,{{ $logo2 }}">
						</td>

					@else
						<td width="35%" class="text-l">
					    <img class="cimg_logo1" src="data:image/png;base64,{{ $logo1 }}">	
						</td>
						<td width="65%" class="text-c">
					    <img class="cimg_logo2" src="data:image/png;base64,{{ $logo2 }}">
						</td>
					@endif
				</tr>
				<tr>
					<td width="100%" style="vertical-align: bottom;" valign="bottom" colspan="2">
						@php $direcciones = explode( "/" , $empresa['EmpLin2'] ); @endphp

				    @foreach( $direcciones as $direccion )
							<p class="direccion">{{ $direccion }}</p>
						@endforeach
						<p class="telefono">{{ $empresa['EmpLin4'] }}</p>
						<p class="email">{{ $empresa['EmpLin3'] }}</p>
					</td>
				</tr>

			</table>
		</td>

		<td class="data_2" style="width: 30% !important">
			<div class="border">
			<p class="empresa_ruc"> R.U.C: <span class="ruc"> {{ $empresa['EmpLin1'] }} </span> </p>
			<p class="factura_titulo"> {{ $nombre_documento }}  </p>
			<p class="factura_titulo"> REMITENTE </p>
			<p class="factura_numero"> N° <span class="factura_serie">{{ $documento_id }} </span> </p>	</div>
		</td>

	</tr>
</table>

