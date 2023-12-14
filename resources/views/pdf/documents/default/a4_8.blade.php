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
  @include('pdf.documents.partials.info_cliente_adicional', [
  'class_name' => 'col-12 mt-x5 mb-x5 pt-x5 pl-x5 pr-x5 pb-x5 border-color-blue-light border-width-1 border-style-solid border-radius-5',
  'nombre_campo_nombre' => 'SEÑOR(ES):',
  'nombre_campo_class' => 'bold font-size-9',
  'right_campo_class' => '',
  'right_text_class' => 'float-right',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold font-size-9',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold font-size-9',
  'direccion' => $cliente->PCDire,
  ])
</div>

@include('pdf.documents.partials.observacion', [ 'class_name' => 'col-12',
'class_name' => 'border-width-1 mb-x4 pt-x5 pl-x5 pr-x5 pb-x5 border-width-1 border-style-solid border-color-blue-light border-radius-5',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => isset($venta['VtaObse']) ? $venta['VtaObse'] : '-'
])

@endslot
@endcomponent

@php
$cant_items = $items->count();

$footerBreak =($cant_items >= 25 && $cant_items <= 45) || ($cant_items>= 88 && $cant_items <= 105) || ($cant_items>= 131);

$classFooter = '';
@endphp

@include('pdf.documents.partials.table', [
'complete_tds_spaces' =>  $footerBreak ? false : true,
'class_name' => 'col-12  border-style-solid border-width-1 border-color-blue-light ' . $footerBreak ? '' : 'container-table-height',
'class_name_table' => 'col-md-12 col-10 border-style-solid border-width-1 border-color-blue-light',
'thead_class' => 'font-size-9 pt-x2 pl-x3 pb-x2 border-right-style-solid border-bottom-style-solid border-width-1 bg-white c-black border-color-blue-light',
'thead_importe_class' => 'border-right-0',
'tbody_class' => 'pt-x2 pl-x3 pb-x2 border-color-blue-light border-right-style-solid border-width-1 border-color-blue-light font-size-8 border-color-blue-light',
'class_precio_unit' => 'text-right pr-x3 border-right-style-solid border-width-1 border-color-blue-light',
'class_importe' => 'text-right pr-x3 border-right-0',
'class_cant' => 'text-right pl-x1 pr-x8 pr-x3 border-right-style-solid border-width-1 border-color-blue-light',
'class_orden' => 'text-center border-right-style-solid border-width-1 border-color-blue-light',
'class_codigo' => 'text-center border-right-style-solid border-width-1 border-color-blue-light',
'class_unidad' => 'text-center border-right-style-solid border-width-1 border-color-blue-light',
'class_descripcion' => 'border-right-style-solid text-align-center border-width-1 border-color-blue-light',
])

    @if($footerBreak)
    @php
    $classFooter = 'position-initial';
    @endphp    
    <div class="page-break"></div>
    @endif

    {{-- FOOTER --}}
    @component('pdf.documents.partials.footer', [
    'class_name' => "mt-x4 border-color-blue-light border-style-solid footer-full-table border-width-1 {$classFooter}"
    ])

    @slot('content')

    <div class="row">
      @include('pdf.documents.partials.info_anexo', [
      'class_name' => 'col-12 border-bottom-style-solid border-width-1 border-color-blue-light',
      ])
    </div>

    <div class="row position-relative">

      @include('pdf.documents.partials.info_adicional', [
      'class_name' => 'col-5 mb-x3',
      'class_qr_div' => 'col-3',
      'is_nota_venta'=> $venta2->isNotaVenta(),
      'info_adicional_class' => $venta2->isNotaVenta() ? "col-10 pl-x5 border-right-style-solid border-width-1 border-color-blue-light" :  "col-7 border-right-style-solid border-width-1 border-color-blue-light",
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
      'class_name' => 'col-5 border-bottom-style-solid border-right-style-solid border-top-style-solid border-width-1 border-color-blue-light ',
      'class_name_table' => 'border-width-1 border-color-blue-light table-with-border',
      'titulo_div_class' => 'bold pl-x3 text-uppercase bold pl-x3 mb-x3 border-bottom-style-solid border-width-1 border-color-blue-light ',
      'tr_titulo_class' => 'bold text-center',
      'tr_valor_class' => 'text-center',
      ])

      @include('pdf.documents.partials.cuentas', [
      'class_name' => 'col-5 border-bottom-style-solid border-top-style-solid border-width-1 border-color-blue-light ',
      'class_name_table' => '',
      'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-1 border-color-blue-light ',
      'cuenta_text_class' => 'pl-x3 ',
      'cuenta_cuenta_text_class' => 'bold',
      ])


    </div>

    <div class="row">

      @include('pdf.documents.partials.firmas', [
      'class_name' => 'col-10',
      ])

    </div>

    @endslot
    @endcomponent
    {{-- /FOOTER --}}

    @endslot
    @endcomponent