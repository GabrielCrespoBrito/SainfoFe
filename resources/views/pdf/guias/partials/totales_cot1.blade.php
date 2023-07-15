@php

$class_name = $class_name ?? "";
$nombre = $nombre ?? "-";
@endphp

<div class="totalescoti {{ $class_name }}">

  {{-- Nombre --}}
  <div class="nombre_div_class col-3 text-left">   
    <span class="nombre_campo_class bold pl-x3"> TOTAL PESO/KGS: </span>
    <span class="nombre_text"> {{ decimal( $cotizacion2->peso(),$decimals) }} </span>
  </div>
  {{-- /Nombre --}}

  @if( $mostrar_igv )
  
    {{-- Subtotal --}}
    <div class="nombre_div_class col-2 text-center">
      <span class="nombre_campo_class bold"> SUB TOTAL: </span>
      <span class="nombre_text"> {{ decimal($cotizacion2->cotbase,$decimals)   }} </span>
    </div>
    {{-- /Subtotal --}}

    {{-- IGV --}}
    <div class="nombre_div_class col-2 text-center">
      <span class="nombre_campo_class bold mr-x5"> IGV: {{ $cotizacion2->items->first()->DetIGVV }}% </span>
      <span class="nombre_text"> {{ decimal($cotizacion['cotigvv'],$decimals) }} </span>
    </div>
    {{-- /IGV --}}

  @endif

  {{-- Total --}}
  <div class="nombre_div_class col-3 text-right">
    <span class="nombre_campo_class bold"> TOTAL S./: </span>
    <span class="nombre_text pr-x3"> {{ decimal($cotizacion['CotTota'],$decimals) }} </span>
  </div>
  {{-- /Total --}}

</div>