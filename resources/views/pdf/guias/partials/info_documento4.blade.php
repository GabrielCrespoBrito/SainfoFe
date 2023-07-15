@php

$infos = [
  [
  'titulo_text' => 'ORDEN COMPRA:',
  'valor_text' => $guia['guipedi'] ? $guia['guipedi'] : "&nbsp"
  ],

  [
  'titulo_text' => 'COD. VEND:',
  'valor_text' => $guia['vencodi']
  ],

  [
  'titulo_text' => 'Nº FACTURA:',
  'valor_text' => $guia['docrefe']
  ],

  [
  'titulo_text' => 'FECHA EMISIÒN:',
  'valor_text' => $guia['GuiFemi'],
  ],

  [
  'titulo_text' => 'FECHA DE INIC. TRASLADO:',
  'valor_text' => $guia['GuiFDes'],
  ],

  [
  'titulo_text' => 'PESO TOTAL:',
  'valor_text' => $guia['guiporp'],
  ],

];

$class_name = $class_name ?? '';
$section_class = $section_class ?? '';
$section_nombre = $section_nombre ?? '';
$section_value = $section_value ?? '';

@endphp


<div class="{{ $class_name }}">

@foreach( $infos as $info )

  <div class="section-info {{ $section_class  }}">
    <span class="{{ $section_nombre }}"> {{ $info['titulo_text'] }}   </span>
    <span class="{{ $section_value }}">  {!! $info['valor_text'] !!} </span>
  </div>

@endforeach

</div>