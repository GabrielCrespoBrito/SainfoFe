@php

$medio_pago_nombre = $medio_pago_nombre ?? null;
$showGuias = $showGuias ?? true;
$showResponsable = $showResponsable ?? true;
$showOrdenCompra = $showOrdenCompra ?? true;
$class_name = $class_name ?? 'col-12 border-width-1 mb-x4 border-bottom-style-dotted';
$valor_td_class = $valor_td_class ?? ' ';

$trs = [

// Fecha de emisiòn
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left ' . $valor_td_class,
'valor_text' => 'Fecha emisiòn:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3 ' . $valor_td_class,
'valor_text' => $venta["VtaFvta"] ,
// ---------------------------------
]],

// Vendedor
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left ' . $valor_td_class,
'valor_text' => 'Vendedor:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3 ' . $valor_td_class,
'valor_text' => $venta2->vendedor->vennomb,
]],


// Forma de Pago
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left ' . $valor_td_class,
'valor_text' => 'Forma de Pago:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3 ' . $valor_td_class,
'valor_text' => $forma_pago->connomb,
]],

];



if($medio_pago_nombre){
  $trs[] = [[
  'class_name' => 'text-top',
  'valor_td_class' => 'valor_td_info bold text-left ' . $valor_td_class,
  'valor_text' => 'Medio de Pago:',
  ],
  [
  'class_name' => 'td_class',
  'valor_td_class' => 'valor_td_info text-left pl-x3 ' . $valor_td_class,
  'valor_text' => $medio_pago_nombre,
  ]];
}


if($showGuias){

  $trs[] = [[
  'class_name' => 'text-top',
  'valor_td_class' => ' bold text-left ' . $valor_td_class,
  'valor_text' => 'Guia/s:',
  ],
  [
  'class_name' => 'td_class',
  'valor_td_class' => ' text-left pl-x3 ' . $valor_td_class,
  'valor_text' => implode(",",$guias),
  ]];

}


if($showResponsable){
  // Responsable
  $trs[] = [[
  'class_name' => 'text-top',
  'valor_td_class' => 'bold text-left ' . $valor_td_class,
  'valor_text' => 'Responsable:',
  ],
  [
  'class_name' => 'td_class',
  'valor_td_class' => 'text-left pl-x3 ' . $valor_td_class,
  'valor_text' => $venta["User_Crea"],
  ]];

}
// Orden de compra

if($showOrdenCompra){

  $trs[] = [[
  'class_name' => 'text-top',
  'valor_td_class' => 'bold text-left ' . $valor_td_class,
  'valor_text' => 'Ord. Compra:',
  ],
  [
  'class_name' => 'td_class',
  'valor_td_class' => 'text-left pl-x3 ' . $valor_td_class,
  'valor_text' => $venta["VtaPedi"],
  ]];

}


@endphp

@include('pdf.documents.partials.info_documento', [
  'class_name' => $class_name
])

