@component('pdf.documents.partials.content', [
'class_name' => 'a4',
'logoMarcaAgua' => null,
'preliminar' => !$venta2->exists,
'logoMarcaAguaSizes' => null,
])

@slot('content')

{{-- HEADER --}}
@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
  'class_name' => 'col-7 c-white'
  ])

  @include('pdf.documents.partials.id', [
  'class_name' => 'col-3 border-style-solid border-radius-5 border-width-1 border-color-gray m-0 h3 bg-gray-800 text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_ruc' => ' bold pt-x6 ',
  'class_nombre' => 'bold h3 pt-0 pl-2 pr-2 ',
  'class_numeracion' => 'bold pb-x6',
  ])

</div>

<div class="row">

  @include('pdf.documents.partials.info_empresa', [ 
  'class_name' => 'col-12',
  'direccion_div_class' => 'bold',
  'correo_text_class' => 'bold',
  'correo_campo_nombre' => $cliente_correo,
  'telefonos_text_class' => 'bold',
  'telefonos_campo_nombre' => 'Telefonos:',
  'telefonos' => $telefonos,
  'direccion' => $direccion
  ])

  @if($logoSubtitulo)
  @include('pdf.documents.partials.logo_subtitulo', [ 'class_name' => 'col-3'])
  @endif

</div>



<div class="row mb-x10"  style="margin-top:5px; margin-bottom: 10px">

  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-6 border-width-1 py-x10 px-x10 border-color-gray border-style-solid border-radius-5  min-h-150',
  'nombre_campo_nombre' => 'DENOMINACIÓN:',
  'nombre_campo_class' => 'bold ',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold ',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "DIRECCIÓN",
  'direccion_campo_class' => 'bold ',
  'direccion' => $cliente->PCDire,
  'vendedor' => false,
  ])

@include('pdf.documents.partials.info_documento3',[
  'class_name' => 'col-4 border-width-1 py-x10 px-x10 border-color-gray border-style-solid border-radius-5 min-h-150',
  'showPagos' => false
])

</div>

{{-- @include('pdf.documents.partials.observacion', [ 
'class_name' => 'col-12',
'class_name' => 'border-width-1 mb-x4 pl-x2 border-style-solid border-radius-5',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => isset($venta['VtaObse']) ? $venta['VtaObse'] : '-'
]) --}}


@endslot
@endcomponent

{{-- /HEADER --}}

@php
$cant_items = $items->count();
$footerBreak = false; 
@endphp

@include('pdf.documents.partials.table', [
'class_name' =>  'mb-x20',
'class_name_table' => 'col-10 odd-background',
'thead_class' => 'py-x4 bg-gray-800-i c-black bold border-solid border-solid-1 border-width-1',
'thead_importe_class' => '',
'tbody_class' => 'pt-x3 pl-x3 pb-x2 font-size-9',
'class_precio_unit' => 'py-x2 text-right pr-x3',
'class_importe' => 'py-x2  text-right pr-x3 ',
'class_cant' => 'py-x2 text-right pl-x1 pr-x8 pr-x3',
'class_orden' => 'py-x2 text-center',
'class_codigo' => 'py-x2 text-center',
'class_unidad' => 'py-x2 text-center',
'class_descripcion' => 'py-x2 ',
])


    {{-- FOOTER --}}
    @component('pdf.documents.partials.footer', [
    'class_name' => "mt-x20 border-color-blue-light border-style-solid footer-full-table border-width-2 position-initial"
    ])

    @slot('content')

    <div class="row">
      @include('pdf.documents.partials.info_anexo', [
      'class_name' => 'col-12 mt-x10 p-x10 border-bottom-style-solid border-width-1 text-center',
      ])
    </div>

    <div class="row position-relative">

      @include('pdf.documents.partials.info_adicional', [
      'class_name' => 'col-5 mb-x3',
      'class_qr_div' => 'col-3',
      'is_nota_venta'=> $venta2->isNotaVenta(),
      'info_adicional_class' => $venta2->isNotaVenta() ? "col-10 pl-x5 " :  "col-7 ",
      'info_nombre_class'=> 'bold',
      'info_text_class'=> '',
      'hash' => $firma ,
      'hora' => $venta2->VtaHora ,
      'peso' => decimal($venta2->getPesoTotal()),
      'nombreDocumento' => $nombre_documento,
      'pageDocumento' => removeHttp(config('app.url_busqueda_documentos')),
      ])

      @include('pdf.documents.partials.totales', [
      'class_name' => 'table-totales col-5 position-absolute mb-x3',
      'style' => 'bottom:0;right:0',
      'class_name_table' => 'border-width-1 table-with-border border-radius-5 border-color-blue-light ',
      'total_nombre_class' => 'bold pl-x5',
      'total_value_class' => 'text-right pr-x5',
      'info_text_consultada_class' => 'bold',
      ]) 

    </div>

    <div class="row">

      @include('pdf.documents.partials.pagos', [
      'class_name' => 'col-5 border-right-style-solid border-top-style-solid border-width-2 border-color-blue-light ',
      'class_name_table' => 'border-width-2 border-color-blue-light table-with-border',
      'titulo_div_class' => 'bold pl-x3 text-uppercase bold pl-x3 mb-x3 border-bottom-style-solid border-width-2 border-color-blue-light ',
      'tr_titulo_class' => 'bold text-center',
      'tr_valor_class' => 'text-center',
      ])

      @include('pdf.documents.partials.cuentas', [
      'class_name' => 'col-5 border-top-style-solid border-width-2 border-color-blue-light ',
      'class_name_table' => '',
      'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-2 border-color-blue-light ',
      'cuenta_text_class' => 'pl-x3 ',
      'cuenta_cuenta_text_class' => 'bold',
      ])

    </div>

    @endslot
    @endcomponent
    {{-- /FOOTER --}}

    @endslot
    @endcomponent