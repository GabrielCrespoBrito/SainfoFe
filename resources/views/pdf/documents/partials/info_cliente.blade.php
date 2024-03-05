@php

$class_name = $class_name ?? "";

// Nombre
$nombre = $nombre ?? "";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';

// Direccion
$direccion = isset($direccion) ? $direccion : '';
$direccion_div_class = $direccion_div_class ?? '';
$direccion_campo_nombre = $direccion_campo_nombre ?? '';
$direccion_campo_class = $direccion_campo_class ?? '';
$direccion_text_class = $direccion_text_class ?? '';

// Observación
$observacion = $observacion ?? null;

$documento_div_class = $documento_div_class ?? '';
$documento_text_class = $documento_text_class ?? '';

$contacto = $contacto ?? "";
$contacto_div_class = $contacto_div_class ?? '';
$contacto_campo_nombre = $contacto_campo_nombre ?? '';
$contacto_campo_class = $contacto_campo_class ?? '';
$contacto_text_class = $contacto_text_class ?? '';

// Vendedor
$vendedor = $vendedor                           ?? "";
$vendedor_div_class = $vendedor_div_class       ?? '';
$vendedor_campo_nombre = $vendedor_campo_nombre ?? '';
$vendedor_campo_class = $vendedor_campo_class   ?? '';
$vendedor_text_class = $vendedor_text_class     ?? '';


// Vendedor
$fecha_emision = $fecha_emision ?? "";
$fecha_emision_div_class = $fecha_emision_div_class ?? '';
$fecha_emision_campo_nombre = $fecha_emision_campo_nombre ?? '';
$fecha_emision_campo_class = $fecha_emision_campo_class ?? '';
$fecha_emision_text_class = $fecha_emision_text_class ?? '';


@endphp

<div class="info_cliente {{ $class_name }}">

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


  {{-- Documento  --}}
  @if($documento)
  <div class="documento_div_class {{ $documento_div_class }}">

    @if($documento_campo_nombre)
    <span class="documento_campo_class {{ $documento_campo_class }}">
      {{ $documento_campo_nombre }}
    </span>
    @endif
    <span class="documento_text {{ $documento_text_class }}"> {{ $documento }} </span>
  </div>
  @endif
  {{-- /Documento --}}

  {{-- Direcciòn  --}}
  @if($direccion)
  <div class="direccion_div_class {{ $direccion_div_class }}">

    @if($direccion_campo_nombre)
    <span class="direccion_campo_class {{ $direccion_campo_class }}">
      {{ $direccion_campo_nombre }}
    </span>
    @endif

    <span class="direccion_text {{ $direccion_text_class }}"> {!! $direccion !!} </span>

  </div>
  @endif

  {{-- /Direcciòn --}}


  {{-- Observacion  --}}
  @if($observacion)
  <div class="direccion_div_class">
    <span class="direccion_campo_class bold"> OBSERVACION:</span>
    <span class="direccion_text"> {{ $observacion }} </span>
  </div>
  @endif
  {{-- /Direcciòn --}}


  {{-- Contacto  --}}
  @if($contacto)
  <div class="contacto_div_class {{ $contacto_div_class }}">

    @if($contacto_campo_nombre)
    <span class="contacto_campo_class {{ $contacto_campo_class }}">
      {{ $contacto_campo_nombre }}
    </span>
    @endif

    <span class="contacto_text {{ $contacto_text_class }}"> {{ $contacto }} </span>

  </div>
  @endif
  {{-- /Contacto --}}


  {{-- Vendedor  --}}
  @if($vendedor)
  <div class="vendedor_div_class {{ $vendedor_div_class }}">

    @if($vendedor_campo_nombre)
    <span class="vendedor_campo_class {{ $vendedor_campo_class }}">
      {{ $vendedor_campo_nombre }}
    </span>
    @endif

    <span class="vendedor_text {{ $vendedor_text_class }}"> {{ $vendedor }} </span>

  </div>
  @endif
  {{-- /vendedor --}}


{{-- Vendedor  --}}
@if($fecha_emision)
<div class="fecha_emision_div_class {{ $fecha_emision_div_class }}">

  @if($fecha_emision_campo_nombre)
  <span class="fecha_emision_campo_class {{ $fecha_emision_campo_class }}">
    {{ $fecha_emision_campo_nombre }}
  </span>
  @endif

  <span class="fecha_emision_text {{ $fecha_emision_text_class }}"> {{ $fecha_emision }} </span>

</div>
@endif
{{-- /fecha_emision --}}



</div>