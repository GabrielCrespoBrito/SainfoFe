@php

$class_name = $class_name ?? "";

$table_class = $table_class ?? '';
$right_text_class = $right_text_class ?? '';

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
$vendedor = $vendedor ?? "";
$vendedor_div_class = $vendedor_div_class ?? '';
$vendedor_campo_nombre = $vendedor_campo_nombre ?? '';
$vendedor_campo_class = $vendedor_campo_class ?? '';
$vendedor_text_class = $vendedor_text_class ?? '';

$fecha_emision = $fecha_emision ?? "";
$fecha_emision_div_class = $fecha_emision_div_class ?? '';
$fecha_emision_campo_nombre = $fecha_emision_campo_nombre ?? '';
$fecha_emision_campo_class = $fecha_emision_campo_class ?? '';
$fecha_emision_text_class = $fecha_emision_text_class ?? '';

@endphp

[ //
'class_name' => '',
'table_class' => '',
'data' => [
[ [], [], [] ],
[ [], [], [] ],
]

]

<div class="{{ $class_name }}">
  <table class="width-{{ $width ?? 100 }} {{ $table_class }}">
    {{ $content }}
  </table>
</div>

<!-- ............................. -->




@component('...table', [ 'class_name' => 'aa', 'class_name' => 'table_class' ])
@slot('content')

    @component('...table_content', [ 'class_name' => 'aa', 'class_name' => 'table_class' ])
    @slot('content')
    @encomponent

@endslot
@encomponent