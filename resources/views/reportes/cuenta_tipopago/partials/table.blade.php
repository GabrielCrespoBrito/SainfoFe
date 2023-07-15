<p> <strong>{{ $tipo_pago_nombre }}</strong></p>
<table class="table-contenido table-pagos" width="100%">
  <thead>
    <tr>
      <td width="5%"> Nro Op. </td>
      <td width="7%"> F.Pago </td>
      <td width="8%"> Nª Doc </td>
      <td> Razòn Social </td>
      <td width="5%"> Voucher </td>
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
      <td> {{ $item->VtaNume }} </td>
      <td> {{ $item->getMonedaNombre() }} </td>
      <td class="text-align-right"> {{ $item->PagTCam }} </td>
      <td class="text-align-right"> {{ $item->PagImpo }} </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    
    <tr class="total">
      <td colspan="4" class="text-align-right">
        <strong>TOTAL {{ $soles_abbre }} </strong> {{ decimal( $general_total_soles += $items->where('MonCodi','01')->sum('PagImpo')) }}
      </td>

      <td colspan="5" class="text-align-right">
        <strong>TOTAL {{ $dolar_abbre }} </strong> {{ decimal( $general_total_dolares +=  $items->where('MonCodi','02')->sum('PagImpo')) }}
      </td>
    </tr>


    @if( $loop->last )
    <tr class="total-general">
      <td colspan="4" class="text-align-right">
      <span style="float:left;font-weight:bold">TOTAL GENERAL</span>
        <strong>TOTAL {{ $soles_abbre }} </strong> s. {{ decimal($general_total_soles) }}
      </td>

      <td colspan="5" class="text-align-right">
        <strong>TOTAL {{ $dolar_abbre }} </strong> {{ decimal($general_total_dolares) }}
      </td>
    </tr>
    @endif

  </tfoot>
</table>