@php

$trs = [
[
[
'titulo_text' => 'ORDEN COMPRA',
'valor_text' => $guia['guipedi'] ? $guia['guipedi'] : "&nbsp"
],

[
'titulo_text' => 'COD. VEND.',
'valor_text' => $guia['vencodi']
],

[
'titulo_text' => 'Nº FACTURA',
'valor_text' => $guia['docrefe']
],

[
'titulo_text' => 'FECHA EMISIÒN',
'valor_text' => $guia['GuiFemi'],
],

[
'titulo_text' => 'FECHA DE INIC. TRASLADO:',
'valor_text' => $guia['GuiFDes'],
// 'titulo_td_class' => 'bold text-italic pl-x3 pt-x4 border-width-0',
// 'valor_td_class' => 'valor_td_info font-size-9 text-left pl-x3 pb-x4 border-right-width-1 border-style-solid',
],

],
];

$class_name = $class_name ?? 'text-top td_class mt-x5 border-style-right-solid border-width-1';
$class_name_table = $class_name_table ?? '';

@endphp


@include('pdf.documents.partials.info_documento', [
'class_name' => $class_name,
'class_name_table' => $class_name_table,
'titulo_td_class' => 'bold text-italic font-size-9 pl-x3 pt-x4 text-top ',
'valor_td_class' => 'valor_td_info font-size-9 text-left pl-x3 pb-x4 text-top',
])