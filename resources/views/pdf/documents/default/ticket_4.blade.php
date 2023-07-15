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
  'class_name' => 'col-12 text-center',
  'nombre' => $empresa['EmpNomb'],
  'rubro' => $empresa['EmpLin6'],
  'nombre_text_class' => 'bold',
  'rubro_text_class' => 'bold',
  'direccion_div_class' => '',
  'correo_text_class' => '',
  'telefonos_text_class' => '',
  'telefonos' => $telefonos,
  'direccion' => $direccion,
  'ruc' => $empresa['EmpLin1'],
  ])

</div>

<div class="row">
  @include('pdf.documents.partials.id', [
  'class_name' => 'col-12 m-0 h4 text-center border-bottom-style-dotted border-width-1 mb-x4 pb-x3',
  'class_nombre' => 'bold',
  'class_numeracion' => '',
  ])
</div>

<div class="row">

  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 mb-x5 border-bottom-style-dotted',
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold',
  'direccion' => $cliente->PCDire,
  ])
  
</div>


@include('pdf.documents.partials.observacion', [
'class_name' => 'col-md-12 border-style-bottom-solid border-width-1 mb-x4 border-bottom-style-dotted',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => $observacion ,
])



@include('pdf.documents.partials.info_documento3', [
  'class_name' => 'col-12 mb-x4 td-font-size-8 mb-x4 border-style-bottom-solid border-width-1 border-bottom-style-dotted',
  'showGuias' => false,
  'showOrdenCompra' => false,
  'valor_td_class' => 'font-size-9'
])

@endslot
@endcomponent


@include('pdf.documents.partials.table_ticket', [
'class_name' => 'col-12 td-font-size-9',
'class_name_table' => 'col-md-12 ',
'thead' => 'font-size-9',
'tbody' => 'font-size-9',
])

{{-- /FOOTER --}}
@component('pdf.documents.partials.footer', ['class_name' => 'mt-x4'])


@slot('content')

<div class="row">
  @include('pdf.documents.partials.info_anexo', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1 font-size-9',
  'titulo_info_class' => '',
  ])
</div>

<div class="row">

  @include('pdf.documents.partials.totales', [
  'class_name' => 'table-totales col-12 mb-x3 border-width-1 border-bottom-style-dotted',

  'class_name_table' => 'font-size-9',
  'total_nombre_class' => 'bold font-size-9',
  'total_value_class' => 'text-right font-size-9',
  ])

  @include('pdf.documents.partials.cuentas', [
  'class_name' => 'col-12 border-bottom-style-dotted border-width-1 font-size-9',
  'class_name_table' => 'font-size-9',
  'titulo_div_class' => 'bold mb-x3 font-size-9',
  'cuenta_text_class' => 'font-size-9',
  'cuenta_cuenta_text_class' => 'bold font-size-9',
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

  @include('pdf.documents.partials.info_adicional', [
  'class_name' => 'col-12',
  'class_qr_div' => 'col-12 text-center',
  'info_adicional_class' => 'col-12',
  'info_nombre_class'=> '',
  'is_nota_venta'=> $venta2->isNotaVenta(),
  'info_text_class'=> 'bold',
  'hash' => $firma ,
  'hora' => $venta2->VtaHora ,
  'peso' => decimal($venta2->getPesoTotal()),
  'nombreDocumento' => $nombre_documento,
  'pageDocumento' => config('app.url_busqueda_documentos'),
  'pageDocumento' => removeHttp(config('app.url_busqueda_documentos')),
  'info_nombre_consultada_class' => 'bold'
  ])

</div>

@endslot
@endcomponent
{{-- /FOOTER --}}

@endslot
@endcomponent