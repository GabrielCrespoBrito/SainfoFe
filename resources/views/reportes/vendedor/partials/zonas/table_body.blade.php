
  <tbody class="vertical-align-top font-size-7">
  @foreach( $items as $itemVendedor )

    @foreach( $itemVendedor as $cliente )
    <tr class="border-bottom-stripe">
      <td> {{ $cliente->codigo }} </td>
      <td> {{ nombreDocumentoCliente($cliente->tipo_documento) }} </td>
      <td> {{ $cliente->documento }}  </td>
      <td> {{ $cliente->nombre }}  </td>
      <td> {{ $cliente->zona }}  </td>
      <td> {{ $cliente->vendedor }}  </td>
    </tr>
    @endforeach


  @endforeach

</tbody>