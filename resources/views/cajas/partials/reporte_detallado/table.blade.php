<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">       
    <tr class="">
      <td width="10%"></td>
      <td></td>
      <td> <strong> Saldo Inicial </strong></td>
      <td class="text-right"></td>
    <td  width="8%" class="text-right"> <strong>   {{ $saldo_inicial_sol }} </strong> </td>
      <td width="8%"  class="text-right"></td>
      <td width="8%"  class="text-right"></strong> </td>
      <td width="8%"  class="text-right"> <strong> {{ $saldo_inicial_dolar }} </strong> </td>
    </tr>    
    <tr class="tr_item-head">
      <td><strong>N° Documento </strong></td>
      <td><strong> Motivo </strong></td>
      <td><strong> Nombre </strong></td>
      <td class="text-right"> <strong> Ingreso S/ </strong> </td>
      <td class="text-right"> <strong> Egreso S/ </strong> </td>
      <td class="text-right"> <strong> T.Cambio </strong> </td>
      <td class="text-right"> <strong> Ingreso $ </strong> </td>
      <td class="text-right"> <strong> Egreso $  </strong> </td>
    </tr>
    @foreach( $items as $item )
      <tr class="tr_item-elemento with-border-bottom">
        <td> {{ $item['nro_documento'] }} </td>
        <td> {{ $item['motivo'] }} </td>
        <td> {{ $item['nombre'] }} </td>
        <td class="text-right"> {{ $item['ingreso_sol'] }} </td>
        <td class="text-right"> {{ $item['egreso_sol'] }} </td>
        <td class="text-right"> {{ $item['tipo_cambio'] }} </td>
        <td class="text-right"> {{ $item['ingreso_dolar'] }} </td>
        <td class="text-right"> {{ $item['egreso_dolar'] }} </td>
      </tr>
    @endforeach 
    <tr class="totales">
      <td></td>
      <td></td>
      <th> Totales </th>
      <th class="text-right"> {{ decimal($total_ingreso_sol) }} </th>
      <th class="text-right"> {{ decimal($total_egreso_sol) }} </th>      
      <th class="text-right"> </th>
      <th class="text-right"> {{ decimal($total_ingreso_dolar) }} </th>
      <th class="text-right"> {{ decimal($total_egreso_dolar) }} </th>
    </tr>
    <tr class="totales">
      <td></td>
      <td></td>
      <th> Saldo Final </th>
      <th class="text-right">  </th>
      <th class="text-right"> {{ $saldo_final_sol }} </th>      
      <th class="text-right"> </th>
      <th class="text-right"> </th>
      <th class="text-right"> {{ $saldo_final_dolar }} </th>
    </tr>
  </table>
</div>