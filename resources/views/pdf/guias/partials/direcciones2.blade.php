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

@endphp


<div class="{{ $class_name }}">
<table>
  <tr>
    <td width="50%" class="font-size-9 border-width-1 border-color-blue-light border-right-style-solid py-x2 px-x3 text-top">
      <div class="nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> PUNTO DE PARTIDA </div>
      <div>       

      <div>
      <span class="bold"> DIRECCIÓN: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidirp }} </span>
      </div>
      <div>
      <span class="bold"> UBIGEO: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidisp }} </span>
      </div>      

    </td>

    <td width="50%" class="font-size-9  py-x2 px-x3 text-top">
      <div class="nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> PUNTO DE LLEGADA </div>
      </div>       
      
      <div>
      <span class="bold"> DIRECCIÓN: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidill }} </span>
      </div>

      <div>
      <span class="bold"> UBIGEO: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guidisll }} </span>
      </div>      

    </td>
    
  </tr>
</table>
</div>