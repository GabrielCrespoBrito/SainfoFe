@php
$is_credito = $is_credito ?? false;
$class_name = $class_name ?? "";
$table_class = $table_class ?? "";
$titulo_div_class = $titulo_div_class ?? '';
$tr_titulo_class = $tr_titulo_class ?? '';
$tr_valor_class = $tr_valor_class ?? '';
$is_credito = $venta2->isCredito();
$creditos = $is_credito ? $venta2->getCreditos() : false ;
$total = $is_credito ? $creditos->sum('monto') : ($venta2->VtaImpo - $venta2->retencionMonto());
@endphp

<div class="{{ $class_name }}">
  
  <div class="titulo_div_class {{ $titulo_div_class }}"> 
    @if( $is_credito )
    Monto neto Pendiente de pago:<span class="total-pendiente-pago"> {{ $moneda_abreviatura }} {{ fixedValue($total,$decimals) }} </span> 
    @else
    -
    @endif
  </div> 

  <table class="{{ $table_class }}" width="100%">
    @if( $is_credito )
    <thead>
    <tr>
      <td class="tr_head {{ $tr_titulo_class }}"> Nº Cuota </td>
      <td class="tr_head {{ $tr_titulo_class }}"> Fec. Venc. </td>
      <td class="tr_head {{ $tr_titulo_class }}"> Monto </td>
    </tr>
    <thead>
    @endif
    <tbody>
      @if( $is_credito )
        @foreach( $creditos as $credito )
        <tr style="text-align: left;">
          <td class="{{ $tr_valor_class }}"> {{ (int) $credito->item }} </td>
          <td class="{{ $tr_valor_class }}"> {{ $credito->fecha_pago }} </td>
          <td class="{{ $tr_valor_class }}"> {{ $credito->getMonedaAbreviatura() }} {{ $credito->monto }} </td>
        </tr>
        @endforeach
      @endif
    <tbody>
  </table>


</div>