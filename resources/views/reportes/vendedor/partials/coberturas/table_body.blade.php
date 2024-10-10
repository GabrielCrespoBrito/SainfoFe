  <tbody class="vertical-align-top font-size-7">
  

{{-- Items = Vendedores --}}
  @foreach( $items as $vendedorId => $vendedorData )


    @foreach ($vendedorData['items'] as $coberturaId => $cobertura)


    <tr class="border-bottom-stripe">
      <td style="padding: 0 5px"> {{ $vendedorData['info']['id'] }} </td>
      <td style="padding: 0 5px"> {{ $vendedorData['info']['nombre_complete'] }} </td>
      <td class="text-align-right" style="padding: 0 5px"> {{ decimal($cobertura['total']['cantidad']) }} </td>
      <td class="text-align-right" style="padding: 0 5px"> {{ decimal($cobertura['total']['importe']) }} </td>
      <td class="text-align-right" style="padding: 0 5px"> {{  $cobertura['info']['cliente_codigo'] }}  </td>
      <td class="text-align-left" style="padding: 0 5px"> {{  $cobertura['info']['cliente']   }}  </td>
    </tr>


    @endforeach

    @include('reportes.vendedor.partials.coberturas.table_total',[ 
      'nameTotal' => "TOTAL VENDEDOR", 
      'codigo' => $vendedorId,
      'cantidad' => decimal($vendedorData['total']['cantidad']), 
      'importe' => decimal($vendedorData['total']['importe']), 
      'total' => $vendedorData['total']['total_coberturas']
    ])

  @endforeach

</tbody>