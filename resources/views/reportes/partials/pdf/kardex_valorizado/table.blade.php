<div class="div_items" style="width:100%">
  <table class="table_items" id="table_venta" width="100%">       

    @include('reportes.partials.pdf.kardex_valorizado.table.header')

    
    @foreach( $data as $producto )

      @php
        $total = end($producto['items'])['saldo'];
      @endphp

    {{-- @dd( "data" , end($producto['items'])['saldo'] ); --}}
    @include('reportes.partials.pdf.kardex_valorizado.table.item', [ 'data' => $producto ])

      @include('reportes.partials.pdf.kardex_valorizado.table.item.totales', [
	    'cantidad' => $total['quantity'],
	    'total' => $total['total']
      ])
      
    @endforeach

  </table>
</div>