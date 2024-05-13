@php
  if($logoMarcaAgua){
    $logoMarcaAguaSizes['top'] -= 40;
  }
@endphp
@component('pdf.documents.partials.content', [
  'class_name' => 'a4',
  'logoMarcaAgua' => $logoMarcaAgua,
  'preliminar' => !$venta2->exists,
  'logoMarcaAguaSizes' => $logoMarcaAguaSizes ?? null ])

@slot('content')


{{-- HEADER --}}
@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
  'class_name' => 'col-7 c-white'
  ])

  @include('pdf.documents.partials.id', [
  'class_name' => 'col-3 border-style-solid border-radius-5 border-width-1 m-0 h4  text-center',
  'ruc' => $empresa['EmpLin1'],
  'class_nombre' => 'bold pt-x5 pb-x5',
  'class_ruc' => ' pt-x6 pb-x3',
  'class_numeracion' => ' pt-x3 pb-x6',
  ])

</div>

<div class="row">
  @include('pdf.documents.partials.info_empresa', [ 'class_name' => 'col-12',
  'direccion_div_class' => 'bold',
  'correo_text_class' => 'bold',
  'correo_campo_nombre' => $cliente_correo,
  'telefonos_text_class' => 'bold',
  'telefonos_campo_nombre' => 'Telefonos:',
  'telefonos' => $telefonos,
  'direccion' => $direccion
  ])
</div>

<div class="row">

  @include('pdf.documents.partials.info_cliente', [
  'class_name' => 'col-12 border-width-1 mt-x5 mb-x5 pl-x2 border-style-solid border-radius-5',
  'nombre_campo_nombre' => 'Razón Social:',
  'nombre_campo_class' => 'bold font-size-9',
  'nombre' => $cliente->PCNomb,
  'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
  'documento_campo_class' => 'bold font-size-9',
  'documento' => $cliente->PCRucc,
  'direccion_campo_nombre' => "Dirección",
  'direccion_campo_class' => 'bold font-size-9',
  'direccion' => $cliente->PCDire,
  'contacto' => false,
  'vendedor' => false,
  ])

</div>

@include('pdf.documents.partials.observacion', [ 'class_name' => 'col-12',
'class_name' => 'border-width-1 mb-x4 pl-x2 border-style-solid border-radius-5',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold font-size-9',
'nombre' => isset($venta['VtaObse']) ? $venta['VtaObse'] : '-'
])

@include('pdf.documents.partials.info_documento1')

@endslot
@endcomponent

@php
$cant_items = $items->count();
$classFooter = '';
@endphp

@include('pdf.documents.partials.table', [
'complete_tds_spaces' => true,
'thead_class' => 'font-size-9 pt-x2 pl-x3 pb-x2 c-white border-bottom-style-solid border-width-1',
'tbody_class' => 'pt-x2 pl-x3 pb-x2 font-size-8',
'class_name' => 'col-12 border-style-solid border-width-1',
'class_name_table' => 'col-md-12 table-with-border ',
'class_orden' => 'text-center border-right-style-solid border-width-1',
'class_codigo' => 'text-center border-right-style-solid border-width-1',
'class_unidad' => 'text-center border-right-style-solid border-width-1',
'class_cant' => 'text-right pr-x3 border-right-style-solid border-width-1',
'class_precio_unit' => 'text-right pr-x3 border-right-style-solid border-width-1',
'class_importe' => 'text-right pr-x3',
'class_descripcion' => 'border-right-style-solid border-width-1',
])


@if(($cant_items >= 20 && $cant_items <= 45) || ($cant_items>= 88 && $cant_items <= 105) || ($cant_items>= 131))
    @php
    $classFooter = 'position-initial';
    @endphp
    <div class="page-break"></div>
@endif

    {{-- FOOTER --}}
    @component('pdf.documents.partials.footer', ['class_name' => "bg-white mt-x4 border-style-solid border-width-1 {$classFooter} "])

    @slot('content')

    <div class="row">
      @include('pdf.documents.partials.info_anexo', [
      'class_name' => 'col-12 border-bottom-style-solid border-width-1',
      ])
    </div>

    <div class="row position-relative">

      @include('pdf.documents.partials.info_adicional', [
      'class_name' => 'col-5 mb-x3',
      'class_qr_div' => 'col-3',
      'is_nota_venta' => $venta2->isNotaVenta(),
      'info_adicional_class' => $venta2->isNotaVenta() ? "col-10 pl-x5 border-right-style-solid border-width-1" :  "col-7 border-right-style-solid border-width-1",
      'info_nombre_class'=> 'bold',
      'info_text_class'=> '',
      'hash' => $firma ,
      'hora' => $venta2->VtaHora ,
      'peso' => decimal($venta2->getPesoTotal()),
      'nombreDocumento' => $nombre_documento,
      'pageDocumento' => config('app.url_busqueda_documentos'),
      ])

      @include('pdf.documents.partials.totales', [
      'class_name' => 'table-totales col-5 position-absolute mb-x3',
      'style' => 'bottom:0; right:0',
      'class_name_table' => 'border-width-1 table-with-border border-radius-5',
      'total_nombre_class' => 'bold pl-x5',
      'total_value_class' => 'text-right pr-x5',
      'info_text_consultada_class' => 'bold',
      ])

    </div>

    {{--  LOGO --}}
    <div class="row">
        @php
          $imagen = base64_encode(file_get_contents(public_path('images/pie_custom.png')));
        @endphp
        <img style="padding: 0;margin: 0;z-index:-1000" src="data:image/png;base64, {!! $imagen !!} ">
    </div>

    @endslot
    @endcomponent
    {{-- /FOOTER --}}

    @endslot
    @endcomponent