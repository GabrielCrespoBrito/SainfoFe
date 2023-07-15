@php

$class_name = $class_name ?? "";
$guia2 = $guia2 ?? null;
// Nombre
$nombre = $nombre ?? "-";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$descripcion_campo_class = $descripcion_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';
$direccionDiv = $direccionDiv ?? '';
@endphp

<div class="direcciones {{ $class_name }}">

  {{-- Nombre --}}
  <div class="nombre_div_class {{ $nombre_div_class }}">

    <div class="{{ $direccionDiv }}"> 
      <span class="nombre_campo_class {{ $nombre_campo_class }}"> PUNTO DE PARTIDA: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidirp }} </span>
    </div>

    <div class="{{ $direccionDiv }}">
      <span class="nombre_campo_class {{ $nombre_campo_class }}"> PUNTO DE LLEGADA: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidill }} </span>
    </div>
    
  </div>
  {{-- /Nombre --}}

</div>
