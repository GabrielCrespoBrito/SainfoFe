@php

$class_name = $class_name ?? "";

$titulo = $titulo ?? '';
$titulo_div_class = $titulo_div_class ?? '' ;
$titulo_text_class = $titulo_text_class ?? '' ;

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

// Correo
$documento = $documento ?? "";
$documento_div_class = $documento_div_class ?? '';
$documento_campo_nombre = $documento_campo_nombre ?? '';
$documento_campo_class = $documento_campo_class ?? '';
$documento_text_class = $documento_text_class ?? '';


// Contacto
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

@endphp

<div class="info_cliente {{ $class_name }}">

  {{-- Titulo --}}
  @if( $titulo )
  <div class="titulo_div_class {{ $titulo_div_class }}">
    <span class="titulo_text {{ $titulo_text_class }}"> {{ $titulo }} </span>
  </div>
  @endif
  {{-- /Nombre --}}


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
  <div class="documento_div_class {{ $documento_div_class }}">

    @if($documento_campo_nombre)
    <span class="documento_campo_class {{ $documento_campo_class }}">
      {{ $documento_campo_nombre }}
    </span>
    @endif

    <span class="documento_text {{ $documento_text_class }}"> {{ $documento }} </span>

  </div>
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



</div>