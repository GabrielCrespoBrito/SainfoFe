@php
    // $cotizaciones = $items['items'];
    $cotizaciones = $items;
@endphp

{{-- @dd( $cotizaciones ) --}}

<tbody class="vertical-align-top">
    @foreach ($cotizaciones as $cotizacion)
        <tr class="bold border-bottom-stripe">
            <td> {{ $cotizacion['id'] }} </td>
            <td> {{ $cotizacion['estado'] }} </td>
            <td> {{ $cotizacion['vendedor'] }} </td>
            <td> {{ $cotizacion['fecha'] }} </td>
            <td> {{ $cotizacion['cliente_ruc'] }} </td>
            <td> {{ $cotizacion['cliente_cliente'] }} </td>
            <td> {{ $cotizacion['zona'] }} </td>
            <td class="text-align-right"> {{ $cotizacion['total'] }} </td>
        </tr>

        @foreach ($cotizacion['items'] as $item)
            <tr class="border-bottom-stripe">
                <td style="background-color: #fafafa;border-left:2px solid gray; padding-left:2px"> {{ $item['id'] }}
                </td>
                <td colspan="3" style="background-color: #fafafa;"> {{ $item['nombre'] }} </td>
                <td style="background-color: #fafafa;" class="text-align-right"> {{ $item['cantidad'] }} </td>
                <td style="background-color: #fafafa;" class="text-align-right" colspan="3"> {{ $item['importe'] }}
                </td>
            </tr>
        @endforeach

        {{-- @foreach ($item['items'] as $venta)
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
    @endforeach --}}
    @endforeach

</tbody>
