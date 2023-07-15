<div class="div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">

  <tr class="tr-header">
    <td><strong>ID</strong></td>
    <td><strong>CODIGO</strong></td>
    <td><strong>UNIDAD</strong></td>
    <td><strong>DESCRIPCIÒN</strong></td>
    <td><strong>STOCK</strong></td>
  </tr>
  <tbody>
    @foreach( $items as $item )
      <tr class="tr-body">
        <td>{{ $item->id }} </td>
        <td>{{ $item->codigo }} </td>
        <td>{{ $item->unidad }} </td>
        <td>{{ $item->nombre }} </td>
        <td style="text-align:right">{{ fixedValue($item->stock_total) }} </td>
      </tr>
    @endforeach
  </tbody>
  </table>
</div>
