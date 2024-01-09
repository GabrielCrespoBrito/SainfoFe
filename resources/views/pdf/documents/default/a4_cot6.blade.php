@component('pdf.documents.partials.content', [
'class_name' => 'a4',
'logoMarcaAgua' => $logoMarcaAgua ?? null,
'logoMarcaAguaSizes' => $logoMarcaAguaSizes ?? null,
])

@slot('content')

{{-- HEADER --}}
@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
  'class_name' => 'col-4 c-white'
  ])

  @include('pdf.documents.partials.info_empresa', [
  'class_name' => 'col-3',
  'direccion_div_class' => '',
  'correo_text_class' => '',
  'telefonos_text_class' => '',
  'telefonos_div_class' => '',
  'telefonos' => $telefonos,
  'direccion' => $direccion
  ])

  @include('pdf.documents.partials.id', [
  'class_name' => 'col-3 border-style-solid border-radius-5 border-width-1 border-color-blue-light m-0 h4 yellow text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_ruc' => ' pt-x6 pb-x3 mt-x5',
  'class_nombre' => 'bold pt-x5 pb-x5 color-blue-light',
  'class_numeracion' => 'pb-x6 pt-x13',
  ])

</div>

<div class="row">

  @include('pdf.documents.partials.info_cliente_adicional2', [
  'class_name' => 'col-12 pl-x5 pt-x5 border-width-1 border-color-blue-light mt-x5 pl-x2 border-style-solid',
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold font-size-9',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold font-size-9',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold font-size-9',
  'direccion' => $cliente->PCDire,
  'contacto_campo_nombre' => 'Contacto: ',
  'contacto_campo_class' => 'bold',
  'contacto' => $contacto,
  
  'telefono_campo_nombre' => 'Moneda: ',
  'telefono_campo_class' => 'bold',
  'telefono' => $moneda_abreviatura,

  'vendedor_campo_nombre' => 'Vendedor: ',
  'vendedor_campo_class' => 'bold',
  'vendedor' => $vendedor_nombre,
  'fecha_emision_campo_class' => 'bold',
  'fecha_emision_campo_nombre' => 'Fecha emisión: ',
  'fecha_emision' => $fecha_emision_,
  'forma_pago_nombre' => 'Forma de Pago:',
  'forma_pago' => $forma_pago
  ])

</div>

@include('pdf.documents.partials.observacion', [
'class_name' => 'mb-x4 pl-x2 border-color-blue-light border-style-solid border-width-1 border-top-0',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => $observacion,
])

@endslot
@endcomponent

@include('pdf.documents.partials.table_cot1', [
'completar_tds' => true,
'cant_items_add' => 30,
'class_name' => 'col-10 border-color-blue-light',
'class_name_table' => 'col-10 border-color-blue-light border-style-solid border-bottom-0 border-width-1 border-color-black',
'thead_class' => 'bg-blue-light c-white border-right-style-solid border-color-blue-light border-bottom-style-solid border-width-1 pt-x2 pb-x2 border-color-black',
'tbody_class' => 'td-right-solid-border pt-x2 pb-x2 border-right-style-solid pb-x2 vertical-align-top border-color-blue-light border-bottom-style-solid border-width-1 font-size-9',
'class_precio_unit' => 'text-right  pr-x3 border-right-style-solid border-width-1 border-color-blue-light',
'class_importe' => 'text-right pr-x3 ',
'class_cant' => 'text-right pl-x1 pr-x8 pr-x3 border-left-style-solid border-color-blue-light border-right-style-solid border-width-1',
'class_orden' => 'text-center',
])

<div class="col-10 text-center border-top-0 border-style-solid border-width-1 border-color-blue-light">
  <div class="bold"> SON: {{ $cifra_letra }}</div>
</div>


<div class="col-10 border-top-0 border-style-solid border-width-1 border-color-blue-light">

  @include('pdf.documents.partials.condiciones_cot1', [
  'class_name' => 'col-5 ',
  'class_name_table' => '',
  'titulo_div_class' => 'bold pl-x3 mb-x3 border-color-blue-light border-bottom-style-solid border-width-1',
  'cuenta_text_class' => 'pl-x3',
  'titulo' => 'CONDICIONES DE VENTA',
  'cuenta_cuenta_text_class' => 'bold',
  ])

  @include('pdf.documents.partials.cuentas2', [
  'class_name' => 'col-5 border-left-style-solid border-width-1 border-color-blue-light',
  'class_name_table' => '',
  'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-1 border-color-blue-light',
  'cuenta_text_class' => 'pl-x3',
  'titulo' => 'CUENTAS BANCARIAS',
  'cuenta_cuenta_text_class' => 'bold',
  ])

</div>

@include('pdf.documents.partials.coti_despedida', [
'class_name' => 'col-12 border-style-solid border-top-0 border-width-1 border-color-blue-light ',
'descripcion_class' => 'text-center',
'receptor_class' => 'text-center mt-x30 mb-x1 pt-x10',
'receptor_text_class' => 'text-underline',
'show_peso' => true
])

@endslot
@endcomponent