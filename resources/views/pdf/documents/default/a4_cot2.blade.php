@component('pdf.documents.partials.content', [
'class_name' => 'a4',
'logoMarcaAgua' => null,
'logoMarcaAguaSizes' => null
])

@slot('content')

{{-- HEADER --}}
@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
  'class_name' => 'col-25 c-white'
  ])

  @include('pdf.documents.partials.info_empresa', [
  'class_name' => 'col-45',
  'nombre_text_class' => 'h3 bold c-3077119',
  'nombre' => $empresa['EmpNomb'],
  'direccion_div_class' => 'h4 mb-x8',
  'correo_text_class' => 'bold',
  'telefonos_text_class' => 'bold',
  'telefonos_div_class' => 'h4 mb-x8',
  'telefonos_campo_nombre' => 'Celular: ',
  'correo_campo_nombre' => 'Correos: ',
  'correo_div_class' => 'h4 mt-x8',
  'telefonos' => $telefonos,
  'direccion' => $direccion
  ])

  @include('pdf.documents.partials.id', [
  'class_name' => 'col-3 height-180 border-style-solid border-radius-5 border-width-1 m-0 h4  text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_ruc' => ' pt-x6 pb-x3 mt-x10',
  'class_nombre' => 'bold c-red pt-x5 pb-x5 mt-x10 text-uppercase',
  'class_numeracion' => ' pt-x3 pb-x6 mt-x10',
  ])

</div>


@if($empresa['EmpLin6'])
<div class="row">
  @include('pdf.documents.partials.subtitulo', [
  'class_name' => 'col-12 bold h4 c-1110569 m-0',
  'content' => $empresa['EmpLin6']
  ])
</div>
@endif

<div class="row">

  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 pl-x2 border-style-solid',
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
  'vendedor_campo_nombre' => 'Vendedor: ',
  'vendedor_campo_class' => 'bold',
  'vendedor' => $vendedor,
  'fecha_emision' => $fecha_emision_,
  ])

</div>

@include('pdf.documents.partials.observacion', [
'class_name' => 'mb-x4 pl-x2 border-style-solid border-width-1 border-top-0',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => $observacion,
])

@endslot
@endcomponent

@include('pdf.documents.partials.table_cot1', [
'class_name' => 'col-10',
'class_name_table' => 'col-10 border-style-solid border-bottom-0 border-width-1 border-color-black',
'thead_class' => 'bg-cccccc c-black border-bottom-style-solid border-width-1 pt-x2 pb-x2 border-color-black',
'tbody_class' => 'pt-x2 pb-x2 vertical-align-top border-bottom-style-solid border-width-1',
'class_precio_unit' => 'text-right pr-x3 border-right-style-solid border-width-1',
'class_importe' => 'text-right pr-x3 ',
'class_cant' => 'text-right pl-x1 pr-x8 pr-x3 border-left-style-solid border-right-style-solid border-width-1',
'class_orden' => 'text-center',
])

@include('pdf.documents.partials.totales_cot1', [
'class_name' => 'col-10 border-bottom-style-solid border-right-style-solid border-left-style-solid border-width-1'
])

<div class="col-10 border-top-0 border-style-solid border-width-1">

  @include('pdf.documents.partials.condiciones_cot1', [
  'class_name' => 'col-5 border-right-style-solid border-width-1',
  'class_name_table' => '',
  'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-1',
  'cuenta_text_class' => 'pl-x3',
  'cuenta_cuenta_text_class' => 'bold',
  ])

  @include('pdf.documents.partials.cuentas', [
  'class_name' => 'col-5',
  'class_name_table' => '',
  'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-1',
  'cuenta_text_class' => 'pl-x3',
  'cuenta_cuenta_text_class' => 'bold',
  ])

</div>

@include('pdf.documents.partials.coti_despedida', [
'class_name' => 'col-12 border-style-solid border-top-0 border-width-1 ',
'descripcion_class' => 'text-center',
'receptor_class' => 'text-center mt-x30 mb-x1 pt-x10',
'receptor_text_class' => 'text-underline',
])

@endslot
@endcomponent