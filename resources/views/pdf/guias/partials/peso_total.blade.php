@php

$class_name = $class_name ?? "";
// Nombre
$nombre = $nombre ?? "-";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';
@endphp

<div class="peso_total {{ $class_name }}">
  <div class="nombre_div_class {{ $nombre_div_class }}">
    <span class="nombre_campo_class {{ $nombre_campo_class }}"> Peso total KGM: </span>
    <span class="nombre_text {{ $nombre_text_class }}"> {{ $guia['guiporp'] }} </span>
  </div>
</div>