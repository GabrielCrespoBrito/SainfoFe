<table width="100%" class="table_data">
  <tr class="header">
    @php
    $isPorPagar = $isPorPagar ?? true;
    @endphp
    <th width="10%"> Fecha {{ $isPorPagar ? 'Cpa' : 'Vta' }} </th>
    <th width="10%"> Fecha {{ $isPorPagar ? 'Vcto' : 'Vcto' }} </th>
    <th width="10%"> Ruc </th>
    <th width="25%"> Razon social </th>
    <th> N° Oper </th>
    <th class="10%" class="text-right"> T.D </th>
    <th width="15%"> N° Doc </th>
    <th width="5%"> Mon </th>
    <th class="border-right text-right"> Total </th>
    <th class="border-right text-right"> Pagado </th>
    <th class="text-right"> Saldo </th>
  </tr>

  @if($agrupacion == "cliente")

  @foreach( $data['docs'] as $clienteData )

  <tr class="tr_nombre">
    <th colspan="11"> {{ $clienteData['info'] }} </th>
  </tr>

  @foreach( $clienteData['docs'] as $venta )
    @include('cajas.partials.reporte_porpagar.tr_body', ['venta' => $venta ])
  @endforeach

  @include('cajas.partials.reporte_porpagar.tr_total', ['sol' => $clienteData['total']['01'] , 'dolar' => $clienteData['total']['02'] , 'subTotal' => true ])


  @endforeach

  @else

  @foreach( $data['docs'] as $venta )
    @include('cajas.partials.reporte_porpagar.tr_body', ['venta' => $venta ])
  @endforeach

  @endif

  @include('cajas.partials.reporte_porpagar.tr_total', ['sol' => $data['total']['01'] , 'dolar' => $data['total']['02'] ])

</table>