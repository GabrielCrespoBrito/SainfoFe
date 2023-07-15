<table width="100%" class="table_data">
  {{-- Header --}}
  <tr class="header">
    @php
      $isPorPagar = $isPorPagar ?? true;
    @endphp
    <th width="10%"> Fecha {{ $isPorPagar ? 'Cpa' : 'Vta' }} </th>
    <th width="10%"> Fecha {{ $isPorPagar ? 'Cpa' : 'Vta' }} </th>
    <th width="10%"> Ruc </th>
    <th width="25%"> Razon social </th> 
    <th width="10%"> N° Oper </th>

    <th width="4%"> T.D </th>
    <th width="15%"> N° Doc </th>
    <th width="6%"> Mon </th>
    <th width="5%" class="text-right"> Total </th>
    <th width="5%" class="text-right"> Pagado </th>
    <th width="5%" class="text-right"> Saldo </th>          
  </tr>
  {{-- Header --}}

  @php      
    $totalSoles = 0;
    $totalDolar = 0;
  @endphp

  {{-- Body --}}
  @if($agrupacion == "cliente")
    @foreach( $ventas as $keyCliente => $ventas_cliente )
      <tr class="tr_nombre">
        @php

          $cliente = $ventas_cliente->first()->cliente_with;
          $solCliente = 0;
          $dolarCliente  = 0;
          
          $totalSoles += $solCliente = $ventas_cliente->where($campos['moncodi'], '01')->sum( $campos['saldo'] );  
          $totalDolar += $dolarCliente = $ventas_cliente->where($campos['moncodi'], '02')->sum($campos['saldo']);

        @endphp
        <th colspan="11"> {{ $cliente->PCCodi }} {{ $cliente->PCNomb }} {{ $cliente->PCRucc }} </th>
      </tr>

      @foreach( $ventas_cliente as $venta )
     @php
        $isSol = $venta->isSol();
        $total = $venta->{$campoTotal};
        if( $venta->isNotaCredito() ){
          $solCliente -= $isSol ? $total : 0;
          $dolarCliente -= $isSol ? 0 :  $total;

          $totalSoles -= $isSol ? $total : 0;
          $totalDolar -= $isSol ? 0 :  $total;
        }
        else {
          $solCliente += $isSol ? $total : 0;
          $dolarCliente += $isSol ? 0 :  $total;          
          $totalSoles += $isSol ? $total : 0;
          $totalDolar += $isSol ? 0 :  $total;
        }
      @endphp

        @include('cajas.partials.reporte_porpagar.tr_body', ['venta' => $venta ])
      @endforeach

      @include('cajas.partials.reporte_porpagar.tr_total', ['sol' => $solCliente , 'dolar' => $dolarCliente , 'subTotal' => true ])

    @endforeach
    
  @else

    @foreach( $ventas as $venta )
      @php

        if( $venta->isNotaCredito() ){
          $totalSoles -= $venta->isSol() ? $venta->{$campoTotal} : 0;
          $totalDolar -= $venta->isSol() ? 0 :  $venta->{$campoTotal};
        }
        else {
          $totalSoles += $venta->isSol() ? $venta->{$campoTotal} : 0;
          $totalDolar += $venta->isSol() ? 0 :  $venta->{$campoTotal};
        }
      @endphp

      @include('cajas.partials.reporte_porpagar.tr_body', ['venta' => $venta ])
    @endforeach
    @php
      // $totalSoles += $ventas->where($campos['moncodi'], '01')->sum( $campoTotal );      
      // $totalDolar += $ventas->where($campos['moncodi'], '02')->sum($campoTotal);
      /*
       $totalSoles2 += $ventas
          ->where( 'TidCodi' , '!=' , '07')
          ->where($campos['moncodi'], '01')
          ->sum( $campoTotal );
        */  
        //dd( $totalSoles, $totalSoles2 );

    @endphp      

  @endif
  {{-- Body --}}

  {{-- Footer --}}
      @include('cajas.partials.reporte_porpagar.tr_total', ['sol' => $totalSoles , 'dolar' => $totalDolar ])
  {{-- Footer --}}

</table>