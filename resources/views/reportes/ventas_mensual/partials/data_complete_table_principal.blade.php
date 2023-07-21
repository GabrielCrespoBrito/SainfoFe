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


{{-- @dd($documento) --}}

  @php
  /*
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
  */
  @endphp

  <tr>
    <td class="strong"> {{ $docCodi }} {{ nombreDocumento($docCodi) }} 
    

    @if($documento['total']['cantidad'])
    <span class="btn btn-default btn-xs show-totales" data-codi="{{ $docCodi }}" style="margin-left:10px"> <span class="fa  fa-calculator"></span>  Totales  </span> 
    @endif
     </td>

    {{-- Total --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => 'all', 'cant' => $documento['total']['cantidad'], 'total' => $documento['total']['total'] ])

    {{-- Aceptadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0001', 'cant' => $documento['0001']['cantidad'], 'total' => $documento['0001']['total']])
    
    {{-- Anuladas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0003', 'cant' =>  $documento['0003']['cantidad'], 'total' => $documento['0003']['total']])

    {{-- Rechazadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0002', 'cant' =>  $documento['0002']['cantidad'], 'total' => $documento['0002']['total']])

    {{-- Pendiente --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', ['status' => '0011', 'cant' =>  $documento['0011']['cantidad'], 'total' => $documento['0011']['total']])
  </tr>

  @if($documento['total']['cantidad'])
  
  @include('reportes.ventas_mensual.partials.table_principal_totales', [ 'totales' => $data['calculos'][$docCodi], 'codi' => $docCodi  ])
  @endif

  @endforeach

</tbody>

<tfoot>

  @php
    $docCodi = "todos";
  @endphp

  <tr>
    <td class="strong"> TOTALES 
    <span class="btn btn-default btn-xs show-totales" data-codi="{{ $docCodi }}" style="margin-left:10px"> <span class="fa  fa-calculator"></span>  Totales  </span> 
    
    </td>

    {{-- Total --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => 'all', 'cant' => $data['docs']['total']['total']['cantidad'], 'total' => $data['docs']['total']['total']['total'] ])

    {{-- Aceptadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0001', 'cant' => $data['docs']['total']['0001']['cantidad'], 'total' => $data['docs']['total']['0001']['total'] ])
    
    {{-- Anuladas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0003', 'cant' => $data['docs']['total']['0003']['cantidad'], 'total' => $data['docs']['total']['0003']['total']])
    

    {{-- Rechazadas --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0002', 'cant' => $data['docs']['total']['0002']['cantidad'], 'total' => $data['docs']['total']['0002']['total'] ])
    

    {{-- Pendiente --}}
    @include('reportes.ventas_mensual.partials.table_principal_slot_data', [ 'docCodi' => $docCodi, 'status' => '0011', 'cant' => $data['docs']['total']['0011']['cantidad'], 'total' => $data['docs']['total']['0011']['total']])

  </tr>

  @include('reportes.ventas_mensual.partials.table_principal_totales', [ 'totales' => $data['calculos']['total'], 'codi' => $docCodi  ])



</tfoot>
</table>