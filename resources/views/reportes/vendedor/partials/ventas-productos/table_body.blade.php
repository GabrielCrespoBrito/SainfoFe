  <tbody class="vertical-align-top font-size-7">
  @foreach( $items as $item )

  <tr>
    <td class="bold border-bottom-solid pt-x2 pb-x2"> {{ $item['info']['id'] }} </td>
    <td class="bold border-bottom-solid pt-x2 pb-x2" colspan="7"> {{ $item['info']['nombre_complete'] }} </td>
  </tr>

    @foreach( $item['items'] as $producto )
    {{-- @dd( $producto ) --}}
    <tr class="border-bottom-stripe">
      <td> {{ $producto['producto_codigo'] }} </td>
      <td> {{ $producto['unidad_nombre'] }} </td>
      <td> {{ $producto['producto_nombre'] }}  </td>
      <td> {{ $producto['tipodocumento'] }}  </td>
      <td> {{ $producto['numero_documento'] }}  </td>
      <td class="text-align-right"> {{ $producto['marca_nombre'] }}  </td>
      <td class="text-align-right"> {{ decimal($producto['cantidad']) }} </td>
      <td class="text-align-right"> {{ decimal($producto['importe']) }}  </td>
    </tr>
    @endforeach

    @include('reportes.vendedor.partials.productos.table_total',[ 'nameTotal' => "{$item['info']['nombre_complete']}", 'cantidad' => decimal($item['total']['cantidad']), 'importe' => decimal($item['total']['importe']) ])

  @endforeach

</tbody>