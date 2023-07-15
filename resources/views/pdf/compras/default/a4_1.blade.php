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

  @include('pdf.documents.partials.info_empresa', [
  'class_name' => 'col-7',
  'nombre_div_class' => 'bold',
  'direccion_div_class' => '',
  'correo_text_class' => 'bold',
  'telefonos_text_class' => 'bold',
  'telefonos_div_class' => '',
  'telefonos_campo_nombre' => 'Celular: ',
  'correo_campo_nombre' => 'Correos: ',
  'correo_div_class' => '',
  'telefonos' => $telefonos ,
  'direccion' => $direccion
  ])

  @include('pdf.documents.partials.id', [
  'class_name' => 'col-3 border-style-solid border-radius-5 border-width-1 m-0 h4 bg-yellow text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_nombre' => 'bold pt-x5 pb-x5 text-uppercase',
  'class_ruc' => ' pt-x6 pb-x3',
  'class_numeracion' => ' pt-x3 pb-x6',
  ])

</div>

<div class="row">
  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 pl-x2 border-style-solid',
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold font-size-9',
  'nombre' => $nombre_empresa,
  'documento_campo_nombre' => $documento_campo_nombre,
  'documento_campo_class' => 'bold font-size-9',
  'documento' => $ruc_empresa,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold font-size-9',
  'direccion' => $direccion_empresa,
  'contacto_campo_nombre' => 'Contacto: ',
  'contacto_campo_class' => 'bold',
  'contacto' => $contacto,
  'vendedor_campo_nombre' => 'Vendedor: ',
  'vendedor_campo_class' => 'bold',
  'vendedor' => $vendedor ?? '',
  'vendedor_nombre' => $vendedor ?? '',
  'fecha_emision_campo_nombre' => 'Fecha Emisión: ',
  'fecha_emision_campo_class' => 'bold',
  'fecha_emision' => $fecha_emision,
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
'class_name_table' => 'col-10 border-style-solid border-width-1 border-color-black',
'thead_class' => 'bg-cccccc c-black border-right-style-solid border-bottom-style-solid border-width-1 pt-x2 pb-x2 border-color-black',
'tbody_class' => 'pt-x2 pb-x2 vertical-align-top border-right-style-solid border-bottom-style-solid border-width-1',
'class_precio_unit' => 'text-right pr-x3 border-width-1',
'class_importe' => 'text-right pr-x3 ',
'class_cant' => 'text-right pl-x1 pr-x8 pr-x3',
'class_orden' => 'text-center',
])

@include('pdf.documents.partials.totales_cot1', [
'class_name' => 'col-10 border-bottom-style-solid border-right-style-solid border-left-style-solid border-width-1'
])

@endslot
@endcomponent