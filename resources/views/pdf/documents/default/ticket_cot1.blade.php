@component('pdf.documents.partials.content', [
  'class_name' => 'ticket'
])

@slot('content')

{{-- HEADER --}}
@component('pdf.documents.partials.header')

@slot('content')

<div class="row">

  @include('pdf.documents.partials.logo', [
    'class_name' => 'col-12 text-center logo-ticket',
    'width' => 200
  ])

  @include('pdf.documents.partials.info_empresa', [ 
  'class_name' => 'col-12 text-center border-bottom-style-dotted border-width-1 mb-x4 pb-x3',
  'nombre' => $empresa['EmpNomb'],
  'nombre_text_class' => 'h5 bold',
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
  'class_name' => 'col-12 m-0 h4 text-center',
  'class_nombre' => 'bold',
  'class_numeracion' => '',
  ])
</div>

<div class="row">
  @include('pdf.documents.partials.info_cliente', [
    'class_name' => 'col-12 border-width-1 mt-x5 mb-x5  border-bottom-style-dotted',
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
'class_name' => 'col-12 border-width-1 mb-x4 border-bottom-style-dotted',
'nombre_campo_nombre' => 'Observacion:',
'nombre_campo_class' => 'bold',
'nombre' => isset($venta['VtaObse']) ? $venta['VtaObse'] : '-'
])

@endslot
@endcomponent


  @include('pdf.documents.partials.table_ticket', [
  'class_name' => 'col-12',
  'class_name_table' => 'col-md-12',
  ])

    {{-- /FOOTER --}}
    @component('pdf.documents.partials.footer', ['class_name' => 'mt-x4'])

    @slot('content')

    <div class="row">

      @include('pdf.documents.partials.totales_ticket_cot', [
      'class_name' => 'col-10 border-bottom-style-solid border-width-1'
      ])

      @include('pdf.documents.partials.cuentas', [
      'class_name' => 'col-12 border-bottom-style-dotted border-width-1',
      'class_name_table' => '',
      'titulo_div_class' => 'bold mb-x3',
      'cuenta_text_class' => '',
      'cuenta_cuenta_text_class' => 'bold',
      ])

      @include('pdf.documents.partials.coti_despedida', [
      'class_name' => 'col-12 mt-x10 ',
      'descripcion_class' => 'text-center',
      'receptor_class' => 'text-center mt-x30 mb-x1 pt-x10',
      'receptor_text_class' => 'text-underline',
      ])

    </div>

    @endslot
    @endcomponent
    {{-- /FOOTER --}}

  @endslot
  @endcomponent