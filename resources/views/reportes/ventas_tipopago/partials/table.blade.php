<p> <strong>{{ $tipo_pago_nombre }}</strong></p>
<table class="table-contenido table-pagos" width="100%">
  <thead>
    <tr>
      <td width="5%"> Nro Op. </td>
      <td width="7%"> F.Pago </td>
      <td width="8%"> Nª Doc </td>
      <td> Razòn Social </td>
      <td width="7%"> Otro Doc. </td>
      <td width="5%"> Banco </td>
      <td width="5%"> Mon. </td>
      <td class="text-align-right" width="5%"> T.C </td>
      <td class="text-align-right" width="8%"> Importe </td>
    </tr>
  </thead>
  <tbody>
    @foreach( $items as $item )
    <tr class="vertical-align-top">
      <td> {{ $item->VtaOper }} </td>
      <td> {{ $item->PagFech }} </td>
      <td> {{ $item->PagBoch }} </td>
      <td> {{ $item->cliente->PCNomb }} </td>
      <td> {{ $item->CtoOper }} </td>
      <td> {{ $item->Bannomb }} </td>
      <td> {{ $item->MonCodi }} </td>
      <td class="text-align-right"> {{ $item->PagTCam }} </td>
      <td class="text-align-right"> {{ $item->PagImpo }} </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="9" class="text-align-right">
        <div>
          TOTAL SOLES
          {{ decimal($items->where('MonCodi','01')->sum('PagImpo')) }}
        </div>

        <div>
          TOTAL $USD
          {{ decimal($items->where('MonCodi','02')->sum('PagImpo')) }}
        </div>

      </td>

    </tr>
  </tfoot>
</table>