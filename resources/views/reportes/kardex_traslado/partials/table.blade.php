<div class="div_items" style="width:100%">
  <table class="table_items" width="100%">
  <tr class="tr-header">
    <td class="text-left" style="padding-left:10px"><strong>CODIGO</strong></td>
    <td class="text-left" style="padding-left:10px"><strong>DESCRIPCIÒN</strong></td>
    <td class="text-right" style="padding-right:10px"><strong>PESO</strong></td>
    <td class="text-right" style="padding-right:10px"><strong>UNIDAD</strong></td>
    <td class="text-right" style="padding-right:10px"><strong>CANTIDAD</strong></td>
  </tr>
  <tbody>  
    @foreach( $data as $products )

      {{-- @dd($products['guia_origen'], $products['guia_destino']) --}}
      {{-- @dd( $products ) --}}

      @include('reportes.kardex_traslado.partials.traslado_info', [
        'ingreso' => $products['guia_origen'], 
        'salida' => $products['guia_destino']
      ]) 


      @foreach( $products['items'] as $product  )
      

      <tr class="{{ $loop->first ? 'tr-border-top' : '' }}">

        <td class="text-left border-right" style="padding-left:10px"> {{ $product['codigo_producto'] }} </td>
        <td class="text-left border-right" style="padding-left:10px"> {{ $product['nombre_producto'] }} </td>
        <td class="text-right border-right" style="padding-right:10px"> {{ $product['peso'] }} </td>
        <td class="text-right border-right" style="padding-right:10px"> {{ $product['unidad_nombre'] }} </td>
        <td class="text-right border-right" style="padding-right:10px"> {{ $product['cantidad'] }}</td>
      </tr>
      @endforeach
    @endforeach
  </tbody>  
  </table>
</div>