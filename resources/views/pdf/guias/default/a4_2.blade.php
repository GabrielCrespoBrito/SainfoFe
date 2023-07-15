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
  'class_name' => 'col-25 c-white'
  ])

  @include('pdf.guias.partials.info_empresa', [ 
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

  @include('pdf.guias.partials.id', [
  'class_name' => 'col-3 height-180 border-style-solid border-radius-5 border-width-1 m-0 h4 bg-yellow text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_ruc' => ' pt-x6 pb-x3 mt-x10',
  'class_nombre' => 'bold c-red pt-x5 pb-x5 mt-x10',
  'class_numeracion' => ' pt-x3 pb-x6 mt-x10',
  ])

</div>


@if($empresa['EmpLin6'])
<div class="row">
  @include('pdf.guias.partials.subtitulo', [
  'class_name' => 'col-12 bold h4 c-1110569 m-0',
  'content' => $empresa['EmpLin6']
  ])
</div>
@endif

 <div class="row">

  @include('pdf.guias.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 pl-x2 border-style-solid border-radius-5',
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

@if($hasFormato)
  @include('pdf.guias.partials.direcciones', [
  'class_name' => 'col-10 mt-x5 border-style-solid border-width-1 border-radius-5',
  'nombre_campo_class' => 'bold text-uppercase pl-x3',
  'descripcion_campo_class' => 'text-uppercase italic',
  ])

@endif

@include('pdf.guias.partials.info_documento1') 

@endslot
@endcomponent

@include('pdf.guias.partials.table', [
  'class_name' => 'col-12  mt-x5',
  'class_name_table' => 'col-md-12 col-10 border-style-solid border-color-black border-width-1',
  'thead_class' => 'bg-cccccc c-black border-bottom-style-solid border-left-style-solid border-width-1 pl-x3 pt-x3 pb-x3 text-uppercase text-left',
  'tbody_class' => 'border-bottom-style-dotted border-right-style-solid border-width-1 pl-x3 pt-x3 pb-x3 text-left',
  'class_codigo' => 'border-left-style-solid border-width-1',
])

@include('pdf.guias.partials.peso_total', [
'class_name' => 'col-12 mt-x20 border-top-0 border-style-solid border-width-1 ',
'nombre_div_class' => 'text-right',
'nombre_campo_class' => 'bold text-uppercase',
'nombre_text' => 'pr-x10',
])



@if($hasFormato)
@include('pdf.guias.partials.informacion_traslado3', [
'class_name' => 'mt-x4',
'table_class' => ' border-top-style-solid border-bottom-style-solid border-left-style-solid pb-x10 pt-x10 border-width-1',
'border_color' => 'border-color-blue-light',
'nombre_campo_class' => 'bold text-uppercase pl-x3 text-italic text-decoration-underline mb-x5',
'descripcion_campo_class' => 'text-uppercase italic',
])
@endif


@endslot
@endcomponent
