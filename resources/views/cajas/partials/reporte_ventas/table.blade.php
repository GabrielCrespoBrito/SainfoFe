<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">      
    
    @include('cajas.partials.reporte_ventas.thead')

    @foreach( $items['tipos'] as $tipo => $docs )

    @include('cajas.partials.reporte_ventas.tr_nombre_tipo', ['nombre' => $docs['nombreTipo'] ])

      @foreach( $docs['docs'] as $doc )
        <tr>
          <td>{{ $doc['nroDoc'] }}</td>
          <td>{{ $doc['fechaEmision'] }}</td>
          <td>{{ $doc['docRef'] }}</td>
          <td>{{ $doc['clienteRazonSocial'] }}</td>
          <td>{{ $doc['estado'] }}</td>        
          <td>{{ $doc['moneda'] }}</td>        
          <td class="text-right">{{ $doc['importe'] }}</td>
          <td class="text-right">{{ $doc['pago'] }}</td>
          <td class="text-right">{{ $doc['saldo'] }}</td>        
          <td class="text-center">{{ $doc['condicion'] }}</td>
          <td class="text-center">{{ $doc['usuario'] }}</td>
        </tr>
      @endforeach

      @include('cajas.partials.reporte_ventas.total', [
        'isSubTotal' => true,
        'columnaNombre' => $docs['nombreTipo'],
        'pagoDolar' => $docs['totales']["02"]['importe'],
        'saldoDolar' => $docs['totales']["02"]['pago'],
        'importeDolar' => $docs['totales']["02"]['saldo'],
        'pagoSol' => $docs['totales']["01"]['importe'],
        'saldoSol' => $docs['totales']["01"]['pago'],
        'importeSol' => $docs['totales']["01"]['saldo'],
      ])

    @include('cajas.partials.reporte_ventas.tr_empty')
    @include('cajas.partials.reporte_ventas.tr_empty')

      
    @endforeach
        
        {{-- Empty  --}}
    @include('cajas.partials.reporte_ventas.tr_empty')
    @include('cajas.partials.reporte_ventas.tr_empty')

    @include('cajas.partials.reporte_ventas.total', [
      'isSubTotal' => false,
      'pagoDolar' => $items['totales']["02"]['importe'],
      'saldoDolar' => $items['totales']["02"]['pago'],
      'importeDolar' => $items['totales']["02"]['saldo'],
      'pagoSol' => $items['totales']["01"]['importe'],
      'saldoSol' => $items['totales']["01"]['pago'],
      'importeSol' => $items['totales']["01"]['saldo'],       
    ])

  </table>
</div>