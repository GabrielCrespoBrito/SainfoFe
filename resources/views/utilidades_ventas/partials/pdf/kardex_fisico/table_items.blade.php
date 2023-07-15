<div class="container div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">       
    
    <tr class="tr-header">
      <td><strong>Fecha</strong></td>
      <td><strong>Tipo Mov</strong></td>
      <td><strong>Alm</strong></td>
      <td><strong>Razón social</strong></td>
      <td><strong>N° Oper.</strong></td>
      <td><strong>Doc. Refe.</strong></td>
      <td><strong>Ingreso.</strong></td>
      <td><strong>Salida.</strong></td>
      <td><strong>Saldo.</strong></td>
    </tr>

    @foreach( $items_group as $group )
      
      @php $items_desde = $group->where( 'GuiFemi' , '>=' , $fecha_desde ); @endphp

      @if( $items_desde->count() )

    <!-- <table class="table_item" width="100%"> -->
    
      <!-- Cabecera del producto -->
      <tr class="tr_producto_head"> 
        <td> {{ $group->first()->ID      }} </td>
        <td> {{ $group->first()->DetCodi }} </td>
        <td colspan="7"> {{ $group->first()->ProNomb }} </td>
      </tr>
      <!-- /Cabecera del producto -->

      <?php

        $items_anteriores = $group->where('GuiFemi' , '<=' , $fecha_anterior );
        $ingreso_inicial  = 0;
        $salida_inicial   = 0;
        $saldo_inicial    = 0;

        if( $items_anteriores->count() ){
          $ingreso_inicial = $items_anteriores->where('EntSal' , 'I')->sum('Detcant');
          $salida_inicial = $items_anteriores->where('EntSal' , 'S')->sum('Detcant');
          $saldo_inicial = $ingreso_inicial - $salida_inicial;
        }
      ?>

      <!-- Stock inicial producto -->
      <tr class="tr_producto_ini"> 
        <td> {{ $fecha_anterior }} </td>
        <td> ---- </td>
        <td> ---- </td>
        <td> ---- </td>
        <td> Stock ini. </td>
        <td> Stock ini. </td>
        <td> {{ decimal($ingreso_inicial) }} </td>
        <td> {{ decimal($salida_inicial) }} </td>
        <td> {{ decimal($saldo_inicial) }} </td>
      </tr>
      <!-- /Stock inicial producto -->
      
      @php
        $ingreso_total = $ingreso_inicial;
        $salida_total = $salida_inicial;
        $saldo_total = "0.00";
      @endphp     

      @foreach( $items_desde as $item )      
        
        @php

          $ingreso = $item->EntSal == "I" ? $item->Detcant : "0.00";
          $salida = $item->EntSal == "S" ? $item->Detcant : "0.00";
          $saldo = ($saldo_inicial + $ingreso) - $salida;
          $saldo_inicial = $saldo;

          $ingreso_total += $ingreso;
          $salida_total += $salida;          
          $saldo_total += $saldo;

        @endphp
        <tr>
          <td> {{ $item->GuiFemi }} </td>
          <td> {{ $item->TmoCodi }} </td>
          <td> {{ $item->Loccodi }} </td>
          <td> {{ $item->PCNomb }} </td>
          <td> {{ $item->GuiOper }} </td>
          <td> {{ $item->GuiNume }} </td>
          <td> {{ decimal($ingreso) }} </td>
          <td> {{ decimal($salida) }} </td>
          <td> {{ decimal($saldo) }} </td>
        </tr>
      @endforeach
      <tr class="item-totales">
        <td></td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td class="td_total"> {{ decimal($ingreso_total) }} </td>
        <td class="td_total"> {{ decimal($salida_total) }} </td>
        <td class="td_total"> {{ decimal($saldo) }} </td>
      </tr>       
    <!-- </table> -->

    @endif
    @endforeach
    </table>

</div>