@php

$class_name = $class_name ?? "";

// Nombre
$nombre = $nombre ?? "-";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';

@endphp

<div class="observacion {{ $class_name }}">

  {{-- Nombre --}}
  <div class="nombre_div_class {{ $nombre_div_class }}">

    @if($nombre_campo_nombre)
    <span class="nombre_campo_class {{ $nombre_campo_class }}">
      {{ $nombre_campo_nombre }}
    </span>
    @endif

    <span class="nombre_text {{ $nombre_text_class }}"> {{ $nombre }} </span>

  </div>
  {{-- /Nombre --}}

</div>

@isset($cotizacion2)
@if($cotizacion2->isOrdenCompra())
<div class="observacion {{ $class_name }}">
  {{-- Nombre --}}
  <div class="nombre_div_class {{ $nombre_div_class }}">
    @if($nombre_campo_nombre)
    <span class="nombre_campo_class {{ $nombre_campo_class }}">
      Contacto:
    </span>
    @endif
    <span class="nombre_text {{ $nombre_text_class }}">
    <br>
    <span>ESTIMADOS SEÑORES: </span> <br>
    <span>POR MEDIO DE LA PRESENTE LES HACEMOS LLEGAR NUESTRO PEDIDO DE LOS SIGUIENTES MATERIALES:</span>
    </span>
  </div>
  {{-- /Nombre --}}
</div>
@endif
@endif