<table class="table">

<thead>
  <tr>
    <td></td>
    <td class="strong text-center text-uppercase border-width-1 border-style-right-solid" colspan="2">Total Emitido</td>
    <td class="strong text-center text-uppercase border-width-1 border-style-right-solid" colspan="2">Aceptados</td>
    <td class="strong text-center text-uppercase border-width-1 border-style-right-solid" colspan="2">Anulados</td>
    <td class="strong text-center text-uppercase border-width-1 border-style-right-solid" colspan="2">Rechazados</td>
    <td class="strong text-center text-uppercase border-width-1 border-style-right-solid" colspan="2">Pendientes</td>
  </tr>
  </thead>

  <tbody>
  @php
    $cantTotalEmitido = 0;
    $importeTotalEmitido = 0;
    
    $cantTotalAceptados = 0;
    $importeTotalAceptados = 0;
    
    $cantTotalAnulados = 0;
    $importeTotalAnulados = 0;
    
    $cantTotalRechazados = 0;
    $importeTotalRechazados = 0;
    
    $cantTotalPendientes = 0;
    $importeTotalPendientes = 0;
  @endphp



  @foreach( $data['docs'] as $docCodi => $documento )
  @if($docCodi == 52 || $docCodi == "09" || $docCodi == "total")
    @continue
  @endif


  {{-- @dd( $documento ) --}}

  @php
    $cantTotalEmitido += $documento['total'];
    $importeTotalEmitido += $documento['total_importe'];
    
    $cantTotalAceptados += $documento['enviados'];
    $importeTotalAceptados += $documento['enviados_importe'];
    
    $cantTotalAnulados += $documento['anuladas'];
    $importeTotalAnulados += $documento['anuladas_importe'];
    
    $cantTotalRechazados += $documento['no_aceptados'];
    $importeTotalRechazados += $documento['no_aceptados_importe'];
    
    $cantTotalPendientes += $documento['por_enviar'];
    $importeTotalPendientes += $documento['por_enviar_importe'];
  @endphp



  <tr>
    <td class="strong"> {{ $docCodi }} {{ nombreDocumento($docCodi) }}  </td>

    {{-- Total --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => 'all', 'cant' => $documento['total'], 'total' => $documento['total_importe'] ])

    {{-- Aceptadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0001', 'cant' => $documento['enviados'], 'total' => $documento['enviados_importe']])
    
    {{-- Anuladas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0003', 'cant' => $documento['anuladas'], 'total' => $documento['anuladas_importe']])

    {{-- Rechazadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0002', 'cant' => $documento['no_aceptados'], 'total' => $documento['no_aceptados_importe']])

    {{-- Pendiente --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0011', 'cant' => $documento['por_enviar'], 'total' => $documento['por_enviar_importe']])
  </tr>
  @endforeach

</tbody>

<tfoot>

  @php
    $docCodi = "todos";
  @endphp

  <tr>
    <td class="strong"> TOTALES </td>


    {{-- Total --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => 'all', 'cant' => $cantTotalEmitido, 'total' => $importeTotalEmitido ])

    {{-- Aceptadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0001', 'cant' => $cantTotalAceptados, 'total' => $importeTotalAceptados ])
    
    {{-- Anuladas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0003', 'cant' => $cantTotalAnulados, 'total' => $importeTotalAnulados])

    {{-- Rechazadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0002', 'cant' => $cantTotalRechazados, 'total' => $importeTotalRechazados ])

    {{-- Pendiente --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0011', 'cant' => $cantTotalPendientes, 'total' => $importeTotalPendientes])
  </tr>


</tfoot>
</table>