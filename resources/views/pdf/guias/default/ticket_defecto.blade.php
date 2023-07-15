@component('pdf.guias.partials.content', [
  'class_name' => 'ticket',
  'logoMarcaAgua' => null,
  'logoMarcaAguaSizes' => null,
])

@slot('content')

{{-- HEADER --}}
@component('pdf.guias.partials.header')

@slot('content')

<div class="row">

  @include('pdf.guias.partials.logo', [
  'class_name' => 'col-10 c-white text-center'
  ])

</div>

<div class="row">
    @include('pdf.guias.partials.info_empresa' , [ 
    'class_name' => 'col-10 text-center border-bottom-style-dotted border-width-1 mb-x4 pb-x3',
    'nombre_text_class' => 'h3 bold',
    'nombre' => $empresa['EmpNomb'],
    'ruc' => $empresa['EmpLin1'],
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
</div>

 <div class="row">
  @include('pdf.guias.partials.id', [
  'class_name' => 'col-12 m-0 h4 text-center pt-x3 pb-x5',
  'class_nombre' => 'bold',
  'class_numeracion' => '',
  ])
 </div>


 <div class="row">

  @include('pdf.guias.partials.info_cliente', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1',
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold pl-x3',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold pl-x3',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold pl-x3',
  'direccion' => $cliente->PCDire,
  ]) 

</div>

@if($hasFormato && $guia2->isSalida())
<div class="row">
  @include('pdf.guias.partials.direcciones', [
  'class_name' => 'col-10 mt-x5',
  'direccionDiv' => 'border-bottom-style-dotted border-width-1 pb-x5 pt-x5',
  'nombre_campo_class' => 'bold text-uppercase',
  'descripcion_campo_class' => 'text-uppercase italic',
  ])
</div>  
@endif

<div class="row">
@include('pdf.guias.partials.info_documento4',[
  'class_name' => 'col-10 pb-x3 pt-x3 border-bottom-style-dotted border-width-1',
  'section_class' => '',
  'section_nombre' => 'bold',
  'section_value' => 'pl-x3',
]) 
</div>  

@endslot
@endcomponent

@include('pdf.guias.partials.table_ticket', [
'class_name' => 'col-12',
'class_name_table' => 'col-md-12',
])


@if($hasFormato && $guia2->isSalida())
@include('pdf.guias.partials.informacion_traslado4', [
'class_name' => 'mt-x4 font-size-9',
'table_class' => '',
'section_title_div_class' => '',
'section_title_value' => 'bold text-decoration-underline mt-x5 mb-x5',
'section_data_nombre' => 'bold',
'section_data_value' => '',
'section_class' => 'pb-x3 pt-x3 border-top-style-dotted border-bottom-style-dotted border-width-1',
])
@endif

@endslot
@endcomponent
