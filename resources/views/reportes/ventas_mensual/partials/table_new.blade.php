<table width="100%" class="table-items oneline" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr class="header">
      <td rowspan="2"> Fecha emisi&oacuten </td>
      <td rowspan="2"> Fecha Vcto. </td>
      <td colspan="3" style="width: 12%"> Comprobante de pago o Documento </td>
      <td rowspan="2" style="width: 2%"> TE </td>
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
      <td rowspan="2"> Est. Sunat </td>
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

    @foreach( $ventas_group as $ventas_info )

    <tr class="tipo-documento">
      <td colspan="22">
        {{ $ventas_info['info']['codigo'] }} {{ $ventas_info['info']['nombre'] }} </td>
    </tr>

  {{-- @dd( $ventas_info ) --}}

    @foreach( $ventas_info['items'] as $venta )

    <tr>
      <td class="text-center">{{ $venta['info']['fecha_emision'] }} </td>
      <td class="text-center">{{ $venta['info']['fecha_vencimiento'] }} </td>
      <td class="text-center">{{ $venta['info']['tipo_documento']  }}</td>
      <td class="text-center">{{ $venta['info']['serie']  }}</td>
      <td class="text-center">{{ $venta['info']['numero']  }}</td>
      <td class="text-center">{{ $venta['info']['cliente_tipo_documento']  }}</td>
      <td>{{ $venta['info']['cliente_documento']  }}</td>
      <td class="text-left" width="200px">{{ $venta['info']['cliente_nombre']  }}</td>
      <td class="text-right">{{ fixedValue( $venta['total']['base_imponible']  ) }}</td>
      <td class="text-right">{{ fixedValue( $venta['total']['exonerada'] ) }}</td>
      <td class="text-right">{{ fixedValue(  $venta['total']['inafecta'] ) }}</td>
      <td class="text-right">{{ fixedValue(  $venta['total']['isc'] ) }}</td>
      <td class="text-right">{{ fixedValue(  $venta['total']['igv'] ) }}</td>
      <td class="text-right">{{ fixedValue(  $venta['total']['icbper']  ) }}</td>
      <td class="text-right">{{ $venta['total']['importe_soles']  }} </td>
      <td class="text-right">{{ fixedValue(  $venta['total']['tc'] ) }}</td>
      <td class="text-right">{{ $venta['info']['docref_fecha_emision'] }} </td>
      <td class="text-right">{{ $venta['info']['docref_tipo_documento']  }}</td>
      <td class="text-right">{{ $venta['info']['docref_serie'] }}</td>
      <td class="text-right">{{ $venta['info']['docref_numero'] }}</td>
      <td class="text-right"> {{ $venta['total']['importe_dolares'] }} </td>
      <td class="text-right"> {{ $venta['info']['estado'] }} </td>
    </tr>
    @endforeach


    {{-- Totales --}}
    <tr class="total-documento">
      <td colspan="8" class="text-center"> Totales documento: </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['base_imponible'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['exonerada'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['inafecta'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['isc'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['igv'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['icbper'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['importe_soles'] ) }} </td>
      <td class="value text-right" colspan="5"></td>
      <td class="value text-right"> {{ fixedValue( $ventas_info['total']['importe_dolares'] ) }} </td>
      <td class="value text-right"> </td>
    </tr>
    @endforeach

    {{-- Totales --}}
    <tr class="total-documento">
      <td colspan="8" class="text-center"> Totales general: </td>
      <td class="value text-right"> {{ fixedValue( $total['base_imponible'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['exonerada'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['inafecta'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['isc'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['igv'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['icbper'] ) }} </td>
      <td class="value text-right"> {{ fixedValue( $total['importe_soles'] ) }} </td>
      <td class="value text-right" colspan="5"></td>
      <td class="value text-right"> {{ fixedValue( $total['importe_dolares'] ) }} </td>
      <td class="value text-right"> </td>
    </tr>

  </tbody>
</table>