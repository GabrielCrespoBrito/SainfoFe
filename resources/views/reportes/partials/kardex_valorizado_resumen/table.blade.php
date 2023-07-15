<div class="div_items" style="width:100%">
  <table class="table_items table_resumen" id="table_venta" width="100%">       
    @include('reportes.partials.pdf.kardex_valorizado_resumen.table.header')
    @foreach( $data as $producto )
      <tr class="{{ $loop->last ? 'border-bottom-td' : '' }}">
        <td class="row border-left text-center">{{ $producto['code'] }}</td>
        <td class="row border-left"> 05 </td>
        <td class="row border-left">{{ $producto['descripcion'] }}</td>
        <td class="row border-left text-center"> 07 </td>
        <td style="padding-right:10px" class="row border-left text-right"> {{ decimal($producto['stock_inicial']['quantity'], 4 ) }}</td>
        <td style="padding-right:10px" class="row border-left text-right">{{ decimal($producto['cant_total_ingreso'], 4 ) }}</td>
        <td style="padding-right:10px" class="row border-left text-right">{{ decimal($producto['cant_total_salida'], 4 ) }}</td>
        <td style="padding-right:10px" class="row border-left text-right">{{ decimal($producto['stock_final']['quantity'], 4 ) }}</td>
        <td style="padding-right:10px" class="row border-left text-right">{{ decimal($producto['stock_final']['cost_unit'], 4 ) }}</td>
        <td style="padding-right:10px" class="row border-left border-right text-right">{{ decimal($producto['stock_final']['total'], 4 ) }}</td>
      </tr>
    @endforeach 
  </table>
</div>