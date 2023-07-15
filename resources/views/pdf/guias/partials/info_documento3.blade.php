@php


$trs = [

// Fecha de emisiòn
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left',
'valor_text' => 'Fecha emisiòn:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3',
'valor_text' => $venta["VtaFvta"] ,
// ---------------------------------
]],

// Vendedor
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left',
'valor_text' => 'Vendedor:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3',
'valor_text' => $venta2->vendedor->vennomb,
]],

// Forma de Pago
[[
'class_name' => 'text-top',
'valor_td_class' => 'valor_td_info bold text-left',
'valor_text' => 'Forma de Pago:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'valor_td_info text-left pl-x3',
'valor_text' => $forma_pago->connomb,
]],



// Guias
[[
'class_name' => 'text-top',
'valor_td_class' => ' bold text-left',
'valor_text' => 'Guia/s:',
],
[
'class_name' => 'td_class',
'valor_td_class' => ' text-left pl-x3',
'valor_text' => implode(",",$guias),
]],

// Responsable
[[
'class_name' => 'text-top',
'valor_td_class' => 'bold text-left',
'valor_text' => 'Responsable:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'text-left pl-x3',
'valor_text' => $venta["User_Crea"],
]],



// Orden de compra
[[
'class_name' => 'text-top',
'valor_td_class' => 'bold text-left',
'valor_text' => 'Ord. Compra:',
],
[
'class_name' => 'td_class',
'valor_td_class' => 'text-left pl-x3',
'valor_text' => $venta["VtaPedi"],
]],







];


@endphp

@include('pdf.documents.partials.info_documento', [
  'class_name' => 'col-12 border-width-1 mb-x4 border-bottom-style-dotted',
])

