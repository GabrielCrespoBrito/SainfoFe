<div class="div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">

  <tr class="tr-header">
    <td><strong>FECHA</strong></td>
    <td><strong>AÑO</strong></td>
    <td><strong>CODIGO</strong></td>
    <td><strong>DESCRIPCIÒN</strong></td>
    <td><strong>PESO</strong></td>
    <td><strong>UNIDAD</strong></td>
    <td><strong>ALMACEN</strong></td>
    <td><strong>STOCK INICIAL</strong></td>
    <td><strong>INGRESO</strong></td>
    <td><strong>SALIDA</strong></td>
    <td><strong>STOCK ACTUAL</strong></td>
    <td><strong>COSTO</strong></td>
    <td><strong>PROVEEDOR</strong></td>
  </tr>
  <tbody>
    @foreach( $items as $item )
      <tr class="tr-header">
        <td>{{ $item['fecha_id'] }} </td>
        <td>{{ $item['year'] }} </td>
        <td>{{ $item['producto_id'] }} </td>
        <td>{{ $item['descripcion'] }} </td>
        <td>{{ $item['peso'] }} </td>
        <td>{{ $item['unidad'] }} </td>
        <td>{{ $item['almacen'] }} </td>
        <td style="text-align:right">{{ fixedValue($item['stock_inicial']) }} </td>
        <td style="text-align:right">{{ fixedValue($item['ingreso']) }} </td>
        <td style="text-align:right">{{ fixedValue($item['salida']) }} </td>
        <td style="text-align:right">{{ fixedValue($item['stock_actual']) }} </td>
        <td style="text-align:right">{{ fixedValue($item['costo_inicial']) }} </td>
        <td>{{ $item['nombre_proveedor'] }} </td>
      </tr>
    @endforeach
  </tbody>
  </table>
</div>
