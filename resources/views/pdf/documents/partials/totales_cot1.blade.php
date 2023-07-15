<div class="totalescoti {{ $class_name }}">

  {{-- Peso --}}
  <div class="nombre_div_class col-2 text-center">
    <span class="nombre_campo_class bold"> PESO TOTAL: </span>
    <span class="nombre_text"> {{ decimal( $peso, 2 )   }} </span>
  </div>
  {{-- /Peso --}}

  @if( $mostrar_igv )

  {{-- Subtotal --}}
  <div class="nombre_div_class col-2 text-center">
    <span class="nombre_campo_class bold"> SUB TOTAL: </span>
    <span class="nombre_text"> {{ decimal( $base, 2 )   }} </span>
  </div>
  {{-- /Subtotal --}}

  {{-- IGV --}}
  <div class="nombre_div_class col-2 text-center">
    <span class="nombre_campo_class bold mr-x5"> IGV: {{ $igv_porcentaje }}% </span>
    <span class="nombre_text"> {{ decimal( $igv ,2) }} </span>
  </div>
  {{-- /IGV --}}

  @endif

  {{-- Total --}}
  <div class="nombre_div_class col-3 text-right">
    <span class="nombre_campo_class bold"> TOTAL {{ $moneda_abreviatura }}: </span>
    <span class="nombre_text pr-x3"> {{ decimal( $total, 2) }} </span>
  </div>
  {{-- /Total --}}

</div>

