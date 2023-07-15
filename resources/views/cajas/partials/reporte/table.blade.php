<div class="container div_items" style="width:100%">
  <p>{{ $nombre }}</p>
  <table class="table_items" id="table_venta" width="100%">       
    <tr class="tr_item-head">
      <td><strong>Codigo</strong></td>
      <td><strong>Tipo</strong></td>
      <td><strong>Fecha</strong></td>
      <td><strong>Monto S./</strong></td>
      <td><strong>Monto USD./</strong></td>      
      <td><strong>Nombre</strong></td>      
    </tr>
    @if( $items->count() )
      @foreach( $items as $item )
      <tr class="tr_item-elemento">
        <td> {{ $item->Id }} </td>
        <td> {{ $item->motivo->CtoNomb }} </td>
        <td> {{ $item->MocFech }} </td>
        <td> {{ $item->{$n_soles} }} </td>
        <td> {{ $item->{$n_dolar} }} </td>
        <td> {{ $item->MocNomb }} </td>
      </tr>
      @endforeach 
    @endif
  </table>
</div>