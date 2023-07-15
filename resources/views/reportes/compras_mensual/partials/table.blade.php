<table width="100%" class="table-items oneline" border="0" cellspacing="0" cellpadding="0">
	<thead>    
    <tr class="header">
			<td rowspan="2"> Fecha emisi&oacuten </td>
			<td rowspan="2"> Fecha Vcto. </td>
			<td colspan="3" style="width: 12%"> Comprobante de pago o Documento </td>
			<td rowspan="2" style="width: 2%" > TE </td>
			<td rowspan="2"> RUC </td>
			<td rowspan="2" class="text-center" style="width: 15%"> Razon social</td>
			<td rowspan="2"> Base imponible</td>
			<td colspan="2" style="width: 13%"> Importe de operaci&oacute;n</td>
			<td rowspan="2" style="width: 3% !important"> isc</td>
			<td rowspan="2" style="width: 4% !important"> igv</td>
			<td rowspan="2" style="width: 4% !important"> icbper</td>
			<td rowspan="2"> Total Soles</td>
			<td rowspan="2" style="width: 3%"> t.c</td>
			<td colspan="4" style="width: 16%"> Doc Orig. que modifica</td>
			<td rowspan="2"> Total USD </td>
		</tr>
		<tr class="header">
			<td> TD </td>
			<td> Serie </td>
			<td> Numero </td>			
			<td> Exo</td>
			<td> Inaf</td>
			<td> Fecha</td>
			<td> TD</td>
			<td> Serie</td>
			<td> Numero</td>
		</tr>	
	</thead>

	{{-- Tbody --}}

	<tbody>
			@foreach( $data_reporte['items'] as $tidcodi => $documents )
      
        {{-- Columna para indiciar el documento que se esta trabajando --}}
				<tr class="tipo-documento"> 
					<td colspan="21"> {{ $tidcodi }} {{ $documents['info']['nombre'] }} </td>
		    </tr>

          {{-- @dd( $documents['items'] ) --}}

          {{-- Separar en 100 --}}
          @foreach( array_chunk($documents['items'],100) as $documentos )
          @foreach( $documentos as $documento )
          <tr>
            <td class="text-center">{{ $documento['info']['fecha'] }} </td>
            <td class="text-center">{{ $documento['info']['fecha_vencimiento'] }} </td>
            <td class="text-center">{{ $documento['info']['tipo_documento'] }}</td>
            <td class="text-center">{{ $documento['info']['serie'] }}</td>
            <td class="text-center">{{ $documento['info']['numero'] }}</td>
            <td class="text-center">{{ $documento['info']['tipo_documento_cliente'] }}</td>
            <td>{{ $documento['info']['documento_cliente'] }}</td>
            <td class="text-center" width="200px">{{ $documento['info']['nombre_cliente'] }}</td>
            <td class="text-right">{{ fixedValue($documento['total']['base_imponible']) }}</td>
            <td class="text-right">{{ fixedValue($documento['total']['exonerada']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['total']['inafecta']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['total']['isc']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['total']['igv']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['total']['icbper']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['total']['importe_soles']) }}</td>
            <td class="text-right">{{ fixedValue( $documento['info']['tipo_cambio']) }}</td>
            <td class="text-right">{{ $documento['info']['fecha_documento_referencia'] }} </td>
            <td class="text-right">{{ $documento['info']['tipo_documento_referencia']  }}</td>
            <td class="text-right">{{ $documento['info']['serie_documento_referencia'] }}</td>
            <td class="text-right">{{ $documento['info']['numero_documento_referencia'] }}</td>
            <td class="text-right"> {{ $documento['total']['importe_dolares'] }} </td>
          </tr>
          @endforeach
          @endforeach

        {{-- Columna para indiciar el documento que se esta trabajando --}}
				{{-- Totales --}}
				<tr class="total-documento">
				  <td colspan="8" class="value text-center"> Totales {{ $documents['info']['nombre'] }}: </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['base_imponible'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['exonerada'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['inafecta'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['isc'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['igv'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['icbper'] ) }} </td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['importe_soles'] ) }} </td>
				  <td class="value text-right" colspan="5"></td>
				  <td class="value text-right"> {{ fixedValue( $documents['total']['importe_dolares'] ) }} </td>
				</tr>

		@endforeach
				{{-- Totales --}}
				<tr class="total-documento"> 
					<td colspan="8" class="value text-center"> Totales Reporte: </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['base_imponible'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['exonerada'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['inafecta'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['isc'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['igv'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['icbper'] ) }} </td>
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['importe_soles'] ) }} </td>
					<td class="value text-right" colspan="5"></td>	
					<td class="value text-right"> {{ fixedValue( $data_reporte['total']['importe_dolares'] ) }} </td>
				</tr>
	</tbody>
</table>