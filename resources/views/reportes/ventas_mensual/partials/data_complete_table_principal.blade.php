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

{{-- @dd( $data['docs'] ) --}}

  @foreach( $data['docs'] as $docCodi => $documento )
  @if($docCodi == 52 || $docCodi == "09" || $docCodi == "total")
    @continue
  @endif


  {{-- @dd( $documento ) --}}

  <tr>
    <td class="strong"> {{ $docCodi }} {{ nombreDocumento($docCodi) }}  </td>

    {{-- Total --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => 'all', 'cant' => $documento['total'], 'total' => $documento['total_importe'] ?? 0 ])

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

</thead>

</table>