  <tbody class="vertical-align-top font-size-7">
  

{{-- Items = Vendedores --}}
  @foreach( $items as $vendedorId => $vendedorData )


    @foreach ($vendedorData['items'] as $coberturaId => $cobertura)


    <tr class="border-bottom-stripe">
      <td> {{ $vendedorData['info']['id'] }} </td>
      <td> {{ $vendedorData['info']['nombre_complete'] }} </td>
      <td class="text-align-right"> {{ decimal($cobertura['total']['cantidad']) }} </td>
      <td class="text-align-right"> {{ decimal($cobertura['total']['importe']) }} </td>
      <td class="text-align-right"> {{ sprintf('%s - %s', $cobertura['info']['cliente_codigo'] , $cobertura['info']['cliente'])   }}  </td>
    </tr>


    @endforeach

    @include('reportes.vendedor.partials.coberturas.table_total',[ 
      'nameTotal' => "TOTAL VENDEDOR", 
      'codigo' => $vendedorId,
      'cantidad' => decimal($vendedorData['total']['cantidad']), 
      'importe' => decimal($vendedorData['total']['importe']),  ])

  @endforeach

</tbody>