<div class="container div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">       
    <tr class="tr_item-head">
      <td><strong>Fecha</strong></td>
      <td><strong>Tipo Mov</strong></td>
      <td><strong>Alm</strong></td>
      <td><strong>Razón social</strong></td>
      <td><strong>N° Oper.</strong></td>
      <td><strong>Doc. Refe.</strong></td>
      <td class="text-right"><strong>Ingreso.</strong></td>
      <td class="text-right"><strong>Salida.</strong></td>
      <td class="text-right"><strong>Saldo.</strong></td>
    </tr>
    @foreach( $data as $producto )
      
      <!-- Cabecera del producto -->
      <tr class="tr_producto_head"> 
        <td> {{ $producto['info']['id']  }} </td>
        <td> {{ $producto['info']['codigo']  }} </td>
        <td colspan="7"> {{ $producto['info']['nombre']  }} </td>
      </tr>
      <!-- /Cabecera del producto -->

      <!-- Stock inicial producto -->
      <tr class="tr_producto_ini"> 
        <td> {{ $fecha_anterior }} </td>
        <td> ---- </td>
        <td> ---- </td>
        <td> ---- </td>
        <td> Stock ini. </td>
        <td> Stock ini. </td>
        <td class="text-right"> {{ decimal($producto['stock_inicial']['ingreso']) }} </td>
        <td class="text-right"> {{ decimal($producto['stock_inicial']['salida']) }} </td>
        <td class="text-right"> {{ decimal($producto['stock_inicial']['saldo']) }} </td>
      </tr>
      <!-- /Stock inicial producto -->
      
      @foreach( $producto['movimientos'] as $item )                      
        <tr>
          <td> {{ $item['fecha'] }} </td>
          <td> {{ $item['motivo'] }} </td>
          <td> {{ $item['almacen'] }} </td>
          <td> {{ $item['razon_social'] }} </td>
          <td> {{ $item['nro_operacion'] }} </td>
          <td> {{ $item['documento_referencia'] }} </td>
          <td class="text-right"> {{ decimal( $item['ingreso'])  }} </td>
          <td class="text-right"> {{ decimal( $item['salida'])  }} </td>
          <td class="text-right"> {{ decimal( $item['saldo'])  }} </td>
        </tr>
      @endforeach
      <tr class="item-totales total">
        <td></td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> Totales: </td>
        <td class="td_total text-right"> {{ decimal($producto['totales']['ingreso']) }} </td>
        <td class="td_total text-right"> {{ decimal($producto['totales']['salida']) }} </td>
        {{-- <td class="td_total text-right"> {{ decimal($producto['totales']['saldo']) }} </td> --}}
        <td class="td_total text-right"></td>
      </tr>       
    <!-- </table> -->

    @endforeach
    </table>
</div>