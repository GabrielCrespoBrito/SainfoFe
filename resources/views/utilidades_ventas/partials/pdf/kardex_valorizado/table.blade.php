<div class="div_items" style="width:100%">

  <table class="table_items" id="table_venta" width="100%">       

    @include('reportes.partials.pdf.kardex_valorizado.table.header')

    @php
    	const ENTRADA = "entrada";
    	const SALIDA = "salida";
    	const SALDO = "saldo";
    	const CANTIDAD = "cantidad";
    	const COSTO = "costo";
    	const COSTO_TOTAL = "costo_total";
    @endphp

    @foreach( $items_group as $code => $items )
      @include('reportes.partials.pdf.kardex_valorizado.table.item', [ 'code' => $code , 'items' => $items ])
    @endforeach

		{{-- @dd("STOP") --}}

  </table>

</div>