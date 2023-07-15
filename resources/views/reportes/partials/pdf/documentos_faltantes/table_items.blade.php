<div class="container div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">       
    <tr class="tr_item-head">
      <td><strong>VtaOper</strong></td>
      <td><strong>Nume</strong></td>
      <td><strong>Fecha</strong></td>
      <td><strong>Estado</strong></td>      
    </tr>
    @foreach( $items as $item )
    <tr class="tr_item-elemento">
      <td> {{ $item["VtaOper"] }} </td>
      <td> {{ $item["Correlativo"] }} </td>
      <td> {{ $item["Fecha"] }} </td>
      <td> {{ $item["Estado"] }} </td>      
    </tr>
    @endforeach 
  </table>
</div>