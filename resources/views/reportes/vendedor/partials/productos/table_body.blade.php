  <tbody class="vertical-align-top font-size-7">
  @foreach( $items as $item )

  <tr>
    <td class="bold border-bottom-solid pt-x2 pb-x2"> {{ $item['info']['id'] }} </td>
    <td class="bold border-bottom-solid pt-x2 pb-x2" colspan="5"> {{ $item['info']['nombre_complete'] }} </td>
  </tr>

    @foreach( $item['items'] as $producto )
    <tr class="border-bottom-stripe">
      <td> {{ $producto['info']['producto_codigo'] }} </td>
      <td> {{ $producto['info']['unidad_nombre'] }} </td>
      <td> {{ $producto['info']['producto_nombre'] }}  </td>
      <td class="text-align-right"> {{ $producto['info']['marca_nombre'] }}  </td>
      <td class="text-align-right"> {{ decimal($producto['total']['cantidad']) }} </td>
      <td class="text-align-right"> {{ decimal($producto['total']['importe']) }}  </td>
    </tr>
    @endforeach

    @include('reportes.vendedor.partials.productos.table_total',[ 'nameTotal' => "{$item['info']['nombre_complete']}", 'cantidad' => decimal($item['total']['cantidad']), 'importe' => decimal($item['total']['importe']) ])

  @endforeach

</tbody>