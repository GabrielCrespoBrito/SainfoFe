@php

    $class_name = $class_name ?? '';

    // Nombre
    $nombre = $nombre ?? '';
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
    $documento = $documento ?? '';
    $documento_div_class = $documento_div_class ?? '';
    $documento_campo_nombre = $documento_campo_nombre ?? '';
    $documento_campo_class = $documento_campo_class ?? '';
    $documento_text_class = $documento_text_class ?? '';

    // Contacto
    $contacto = $contacto ?? '';
    $contacto_div_class = $contacto_div_class ?? '';
    $contacto_campo_nombre = $contacto_campo_nombre ?? '';
    $contacto_campo_class = $contacto_campo_class ?? '';
    $contacto_text_class = $contacto_text_class ?? '';

    // Vendedor
    $vendedor = $vendedor ?? '';
    $vendedor_div_class = $vendedor_div_class ?? '';
    $vendedor_campo_nombre = $vendedor_campo_nombre ?? '';
    $vendedor_campo_class = $vendedor_campo_class ?? '';
    $vendedor_text_class = $vendedor_text_class ?? '';

    // Vendedor
    $fecha_emision = $fecha_emision ?? '';
    $fecha_emision_div_class = $fecha_emision_div_class ?? '';
    $fecha_emision_campo_nombre = $fecha_emision_campo_nombre ?? '';
    $fecha_emision_campo_class = $fecha_emision_campo_class ?? '';
    $fecha_emision_text_class = $fecha_emision_text_class ?? '';

@endphp

<div style="overflow:hidden" class="info_cliente {{ $class_name }}">

    <div class="col-65">


        {{-- Nombre --}}
        <div class="nombre_div_class {{ $nombre_div_class }}">
            <span class="col-2 nombre_campo_class {{ $nombre_campo_class }}"> Cliente </span>
            <span class="col-8 nombre_text {{ $nombre_text_class }}">:  {{ $nombre }} </span>
        </div>
        {{-- /Nombre --}}

        {{-- Documento  --}}
        <div class="documento_div_class {{ $documento_div_class }}">
            <span class="col-2 documento_campo_class {{ $documento_campo_class }}"> {{ $documento_campo_nombre }} </span>
            <span class="documento_text col-md-8 {{ $documento_text_class }}">:  {{ $documento }} </span>
        </div>
        {{-- /Documento --}}

        {{-- Direcciòn  --}}
        <div class="direccion_div_class {{ $direccion_div_class }}">
          <span class="col-2 direccion_campo_class {{ $direccion_campo_class }}">{{ $direccion_campo_nombre }}</span>
          <span class="col-8 direccion_text {{ $direccion_text_class }}">:  {!! $direccion !!} </span>
        </div>
        {{-- /Direcciòn --}}


        {{-- Contacto  --}}
        @if ($contacto)
            <div class="contacto_div_class {{ $contacto_div_class }}">

                @if ($contacto_campo_nombre)
                    <span class="contacto_campo_class {{ $contacto_campo_class }}">
                        {{ $contacto_campo_nombre }}
                    </span>
                @endif

                <span class="contacto_text {{ $contacto_text_class }}"> {{ $contacto }} </span>

            </div>
        @endif
        {{-- /Contacto --}}


        {{-- Vendedor  --}}
        @if ($vendedor)
            <div class="vendedor_div_class {{ $vendedor_div_class }}">

                @if ($vendedor_campo_nombre)
                    <span class="vendedor_campo_class {{ $vendedor_campo_class }}">
                        {{ $vendedor_campo_nombre }}
                    </span>
                @endif

                <span class="vendedor_text {{ $vendedor_text_class }}"> {{ $vendedor }} </span>

            </div>
        @endif
        {{-- /vendedor --}}



    </div>

    <div class="col-35">


        {{-- fecha emision  --}}
        @if ($fecha_emision)
            <div class="fecha_emision_div_class {{ $fecha_emision_div_class }}">

                <span class="col-4 fecha_emision_campo_class {{ $fecha_emision_campo_class }}">
                Fecha Emision</span>

                <span class="col-6 fecha_emision_text {{ $fecha_emision_text_class }}">: {{ $fecha_emision }} </span>

            </div>
        @endif
        {{-- /fecha_emision --}}

    {{-- Moneda --}}
          <div class="fecha_emision_div_class">
              <span class="col-4  fecha_emision_campo_class">Moneda </span>
              <span class="col-6  fecha_emision_text">: {{ $moneda_abreviatura }} </span>
          </div>
    {{-- /Moneda --}}

    {{-- Medios de Pago --}}
        <div class="fecha_emision_div_class">
            <span class="col-4 fecha_emision_campo_class">Forma de Pago </span>
            <span class="col-6  fecha_emision_text" >
                : {{ optional($forma_pago)->connomb . return_strs_if($medio_pago_nombre, '-', $medio_pago_nombre) }}
            </span>
        </div>
    {{-- Medios de Pago --}}




    </div>

</div>
