<table class="table-contenido" width="100%">
  <thead>
    <tr>
      <td> Id </td>
      <td> Codigo </td>
      <td> Marca </td>
      <td> Nombre </td>
      <td> Unidad </td>
      <td class="text-align-right"> S.Ant </td>
      <td class="text-align-right"> Dif. </td>
      <td class="text-align-right"> S.Nue </td>
      <td class="text-align-right"> Costo </td>
      <td class="text-align-right"> Importe </td>
    </tr>
  </thead>

  <tbody>
    @foreach( $toma_inventario->detalles as $detalle )
    <tr>
      <td> {{ $detalle->Id }} </td>
      <td> {{ $detalle->ProCodi }} </td>
      <td> {{ $detalle->ProMarc }}  </td>
      <td> {{ $detalle->proNomb }}  </td>
      <td> {{ $detalle->UnpCodi }}  </td>
      <td class="text-align-right"> {{ $detalle->ProStock }}  </td>
      <td class="text-align-right"> {{ $detalle->getDiff() }}  </td>
      <td class="text-align-right"> {{ $detalle->ProInve }} </td>
      <td class="text-align-right"> {{ $detalle->ProPUCS }} </td>
      <td class="text-align-right"> {{ $detalle->getImporte() }} </td>
    </tr>
    @endforeach
  </tbody>

</table>