  <tbody class="vertical-align-top font-size-7">
  @foreach( $items as $item )

  <tr>
    <td class="bold border-bottom-solid pt-x2 pb-x2"> {{ $item['info']['id'] }} </td>
    <td class="bold border-bottom-solid pt-x2 pb-x2" colspan="2"> {{ $item['info']['nombre_complete'] }} </td>
    <td colspan="9"></td>
  </tr>

    @foreach( $item['items'] as $venta )
    <tr class="border-bottom-stripe">
      <td> {{ $venta['info']['id'] }} </td>
      <td> {{ $venta['info']['tipo_documento'] }} </td>
      <td> {{ $venta['info']['serie'] }}  </td>
      <td> {{ $venta['info']['numeracion'] }}  </td>
      <td> {{ $venta['info']['fecha_emision'] }} </td>
      <td> {{ $venta['info']['fecha_vencimiento'] }}  </td>
      <td> {{ $venta['info']['fecha_pago'] }}  </td>
      <td> {{ $venta['info']['cliente'] }}  </td>
      <td class="text-align-right"> {{ decimal($venta['total']['importe']) }}  </td>
      <td class="text-align-right"> {{ decimal($venta['total']['pago']) }}  </td>
      <td class="text-align-right"> {{ decimal($venta['total']['saldo']) }}  </td>
      <td class="text-align-right"> {{ $venta['info']['forma_pago'] }}  </td>
    </tr>
    @endforeach

    @include('reportes.vendedor.partials.ventas.table_total',[ 'nameTotal' => "TOTAL {$item['info']['nombre_complete']}", 'importe' => decimal($item['total']['importe']), 'pago' => decimal($item['total']['pago']), 'saldo' => decimal($item['total']['saldo']) ])

  @endforeach

</tbody>