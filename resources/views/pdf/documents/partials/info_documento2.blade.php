@php

$medio_pago_nombre = $medio_pago_nombre ?? null;

$trs = [
[
[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-7 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Fecha emisión',
// ---------------------------------
'valor_td_class' => 'font-size-7 valor_td_info text-left pl-x3',
'valor_text' => $venta["VtaFvta"] ,

// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-7 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Forma de Pago',
// ---------------------------------
'valor_td_class' => 'font-size-7 valor_td_info text-left pl-x3',
'valor_text' => optional($forma_pago)->connomb . return_strs_if($medio_pago_nombre, '-' , $medio_pago_nombre),

// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-7 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Vendedor',
// ---------------------------------
'valor_td_class' => 'font-size-7 valor_td_info text-left pl-x3 size-8',
'valor_text' => $venta2->vendedor->vennomb,


// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-7 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Guía',
// ---------------------------------
'valor_td_class' => 'font-size-7 valor_td_info text-left pl-x3 font-size-8',
'valor_text' => implode("<br>,",$guias),


// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-6 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Responsable:',
// ---------------------------------
'valor_td_class' => 'font-size-6 valor_td_info text-left pl-x3',
'valor_text' => $venta["User_Crea"],

// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

[
// ---------------------------------
'class_name' => 'border-right-style-solid text-top td_class border-style-solid border-width-1',
// ---------------------------------
'titulo_td_class' => 'font-size-7 titulo_td_info bold text-left pl-x3',
'titulo_text' => 'Ord. Compra',
// ---------------------------------
'valor_td_class' => 'font-size-7 valor_td_info text-left pl-x3',
'valor_text' => $venta["VtaPedi"],

// ---------------------------------
'colspan' => null,
'rowspan' => null,
// ---------------------------------
],

],
];


@endphp

@include('pdf.documents.partials.info_documento', [
'class_name' => 'col-12 p-x1 mb-x4',
'class_name_table' => 'border-width-1 table-with-border border-radius-5',
])



