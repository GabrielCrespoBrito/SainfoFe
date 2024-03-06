@component('pdf.documents.partials.content', [
  'class_name' => 'ticket' 
])

@slot('content')

@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
  'class_name' => 'col-100 c-white text-center'
  ])

</div>

<div class="row">

  @include('pdf.documents.partials.info_empresa', [
  'class_name' => 'col-12 text-center border-bottom-style-dotted border-width-1 mb-x4 pb-x3',
  'nombre' => $empresa['EmpLin5'],
  'nombre_text_class' => 'h5 bold',
  'direccion_div_class' => '',
  'correo_text_class' => '',
  'telefonos_text_class' => '',
  'telefonos' => $telefonos,
  'direccion' => $direccion,
  'correo' => false,
  'ruc' => $empresa['EmpLin1'],
  ])

</div>

<div class="row">
  @include('pdf.documents.partials.id', [
  'class_name' => 'col-12 m-0 h5 text-center',
  'class_nombre' => 'bold',
  'class_numeracion' => '',
  ])
</div>

<div class="row">

  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 mb-x5 border-bottom-style-dotted',
  'nombre_campo_nombre' => 'Nombre:',
  'nombre_campo_class' => 'bold',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold',
  'documento' => false,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold',
  'direccion' =>  false,
  ])
  
</div>

@include('pdf.documents.partials.info_documento3', [
  'class_name' => 'col-12 mb-x4',
  'showPagos' => false,
  'showGuias' => false,
  'showResponsable' => false,
  'showOrdenCompra' => false,
])


@endslot
@endcomponent


@include('pdf.documents.partials.table_ticket', [
'class_name' => 'col-12 border-top-style-dotted border-width-1',
'class_name_table' => 'col-md-12 font-size-9',

])

{{-- /FOOTER --}}
@component('pdf.documents.partials.footer', ['class_name' => 'mt-x4'])


@slot('content')

<div class="row">
  @include('pdf.documents.partials.info_anexo', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1',
  'titulo_info_class' => '',
  'cifra_letra' => false
  ])
</div>

<div class="row">

  @include('pdf.documents.partials.totales', [
  'class_name' => 'table-totales col-12 mb-x3 border-width-1 border-bottom-style-dotted',
  'class_name_table' => '',
  'total_nombre_class' => 'bold',
  'total_value_class' => 'text-right',
  ])

  @include('pdf.documents.partials.cuentas', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1',
  'class_name_table' => '',
  'titulo_div_class' => 'bold mb-x3',
  'cuenta_text_class' => '',
  'cuenta_cuenta_text_class' => 'bold',
  ])

  @if($venta2->isCredito())

  @include('pdf.documents.partials.pagos', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1',
  'class_name_table' => '',
  'titulo_div_class' => 'bold text-uppercase bold mb-x3',
  'tr_titulo_class' => 'bold text-center',
  'tr_valor_class' => 'text-center',
  ])

  @endif

<div class="">Hora: {{ $venta2->VtaHora }}</div>

  <div class="text-center pt-x10">
    <div class="text-center">GRACIAS POR SU COMPRA</div>
  </div>



</div>

@endslot
@endcomponent
{{-- /FOOTER --}}

@endslot
@endcomponent