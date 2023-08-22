@component('pdf.guias.partials.content', [
'class_name' => 'a4',
'logoMarcaAgua' => $logoMarcaAgua,
'logoMarcaAguaSizes' => $logoMarcaAguaSizes
])

@slot('content')

{{-- HEADER --}}
@component('pdf.guias.partials.header')

@slot('content')

<div class="row">

  @include('pdf.guias.partials.logo', [
  'class_name' => 'col-4 c-white'
  ])

  @include('pdf.guias.partials.info_empresa', [
  'class_name' => 'col-3',
  'direccion_div_class' => '',
  'correo_text_class' => '',
  'telefonos_text_class' => '',
  'telefonos_div_class' => '',
  'telefonos_campo_nombre' => '',
  'correo_campo_nombre' => '',
  'correo_div_class' => '',
  'telefonos' => $telefonos,
  'direccion' => $direccion
  ])

  @include('pdf.guias.partials.id', [
  'class_name' => 'col-3 border-style-solid border-radius-5 border-width-2 border-color-blue-light m-0 h4 yellow text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_ruc' => ' pt-x6 pb-x3 mt-x5',
  'class_nombre' => 'bold  pt-x5 pb-x5 color-blue-light',
  'class_numeracion' => 'pb-x6 pt-x13',
  ])

</div>


{{--  --}}
 <div class="row">

  @include('pdf.guias.partials.info_cliente', [
  'class_name' => 'col-12 border-width-2 border-color-blue-light mt-x5 pl-x2 border-style-solid border-radius-5',
  'titulo' => $isGuiaTransportista ? 'Datos del Remitente:' : false,
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold pl-x3',
  'nombre' =>  $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold pl-x3',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold pl-x3',
  'direccion' => $isGuiaTransportista ?  false : $cliente->PCDire,
  'contacto' => false,
  ])

  @if($isGuiaTransportista)
    @include('pdf.guias.partials.info_cliente', [
    'class_name' => 'col-12 border-width-1 mt-x3 mb-x5 pl-x2 border-style-solid border-radius-5',
    'titulo' => 'Datos del Destinatario:',
    'nombre_campo_nombre' => 'Razón Social:',
    'nombre_campo_class' => 'bold pl-x3',
    'nombre' =>  $cliente->PCNomb,
    'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
    'documento_campo_class' => 'bold pl-x3',
    'documento' => $cliente->PCRucc,
    'direccion' => false,
    'contacto' => false,
    ])
  @endif

</div>
{{--  --}}

@if($hasFormato)

@include('pdf.guias.partials.direcciones2', [
'class_name' => ' row mt-x5 border-width-2 border-color-blue-light border-style-solid border-radius-5',
'nombre_campo_class' => 'bold text-center text-uppercase',
'descripcion_campo_class' => 'text-uppercase italic',
])

@include('pdf.guias.partials.informacion_traslado3', [
'class_name' => 'mt-x4',
'table_class' => ' border-top-style-solid border-bottom-style-solid border-left-style-solid pb-x10 pt-x10 border-width-1',
'border_color' => 'border-color-blue-light',
'nombre_campo_class' => 'bold text-uppercase pl-x3 text-italic text-decoration-underline mb-x5',
'descripcion_campo_class' => 'text-uppercase italic',
])

@endif

@include('pdf.guias.partials.info_documento1',[
'class_name' => 'text-top td_class mt-x5 border-width-2 border-color-blue-light border-style-solid border-radius-5',
'class_name_table' => 'border-width-0 border-color-white table-with-border',
])

@endslot
@endcomponent

@include('pdf.guias.partials.table', [
'complete_tds' => true,
'cant_tds'     => 35,
'class_name'   => 'col-12 mt-x5',
'class_name_table' => 'col-md-12 col-10 border-style-solid border-color-blue-light border-width-2',
'thead_class'  => 'bg-blue-light c-white border-bottom-style-solid border-left-style-solid border-width-2 pl-x3 pt-x3 pb-x3 text-uppercase text-left border-color-blue-light' ,
'tbody_class'  => 'border-color-blue-light font-size-9 border-right-style-solid border-width-2 pl-x3 text-left',
'class_codigo' => 'border-left-style-solid border-color-blue-light border-width-2',
'class_peso'   => 'text-right pr-x5',

])


@endslot
@endcomponent