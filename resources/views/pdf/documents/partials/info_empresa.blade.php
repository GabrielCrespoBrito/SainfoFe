@php
$class_name = $class_name ?? "";


// Nombre
$nombre = $nombre ?? "";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';

$rubro = $rubro ?? "";
$rubro_div_class = $rubro_div_class ?? '';
$rubro_text_class = $rubro_text_class ?? '';

// Direccion
$direccion = $direccion ?? "";
$direccion_div_class = $direccion_div_class ?? '';
$direccion_campo_nombre = $direccion_campo_nombre ?? '';
$direccion_campo_class = $direccion_campo_class ?? '';
$direccion_text_class = $direccion_text_class ?? '';

// Telefonos
if(isset($telefonos)){
  $telefonos = $telefonos ?? "";
}
$telefonos_div_class = $telefonos_div_class ?? '';
$telefonos_campo_nombre = $telefonos_campo_nombre ?? '';
$telefonos_campo_class = $telefonos_campo_class ?? '';
$telefonos_text_class = $telefonos_text_class ?? '';

// Correo
$correo = $correo ?? "";
$correo_div_class = $correo_div_class ?? '';
$correo_campo_nombre = $correo_campo_nombre ?? '';
$correo_campo_class = $correo_campo_class ?? '';
$correo_text_class = $correo_text_class ?? '';

// Ruc
$ruc = $ruc ?? "";
$ruc_div_class = $ruc_div_class ?? '';
$ruc_text_class = $ruc_text_class ?? '';


@endphp

<div class="info_empresa {{ $class_name }}">

  {{-- Nombre  --}}
  @if( $nombre )
  <div class="nombre_div_class {{ $nombre_div_class }}">

    @if($nombre_campo_nombre)
    <div class="nombre_campo_class {{ $nombre_campo_class }}">
      {{ $nombre_campo_nombre }}
    </div>
    @endif

    <div class="nombre_text {{ $nombre_text_class }}"> {{ $nombre }} </div>

  </div>
  @endif
  {{-- /Nombre --}}

  {{-- Rubro  --}}
  @if( $rubro )
  <div class="nombre_div_class {{ $rubro_div_class }}">
    <div class="nombre_text {{ $rubro_text_class }}"> {{ $rubro }} </div>

  </div>
  @endif
  {{-- /Rubro --}}


  {{-- Direcciòn  --}}
  <div class="direccion_div_class {{ $direccion_div_class }}">

    @if($direccion_campo_nombre)
    <div class="direccion_campo_class {{ $direccion_campo_class }}">
      {{ $direccion_campo_nombre }}
    </div>
    @endif

    <div class="direccion_text {{ $direccion_text_class }}"> {!! $direccion !!} </div>

  </div>
  {{-- /Direcciòn --}}

  {{-- RUC --}}
  @if( $ruc )
  <div class="ruc_div_class {{ $ruc_div_class }}">
    <div class="ruc_text {{ $ruc_text_class }}"> R.U.C. {{ $ruc }} </div>
  </div>
  @endif
  {{-- /RUC --}}


  {{-- Telefonos  --}}
  @if($telefonos)
  <div class="telefonos_div_class {{ $telefonos_div_class }}">

    @if($telefonos_campo_nombre)
    <span class="telefonos_campo_class {{ $telefonos_campo_class }}">
      {{ $telefonos_campo_nombre }}
    </span>
    @endif

    <span class="telefonos_text {{ $telefonos_text_class }}"> {{ $telefonos }} </span>

  </div>
  @endif
  {{-- /Telefonos --}}

  {{-- Emails  --}}
  <div class="email_div_class {{ $correo_div_class }}">

    @if($correo_campo_nombre)
    <span class="correo_campo_class {{ $correo_campo_class }}">
      {{ $correo_campo_nombre }}
    </span>
    @endif

    <span class="correo_text {{ $correo_text_class }}"> {{ $correo }} </span>

  </div>
  {{-- /Emails --}}

</div>