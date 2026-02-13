@extends('layouts.master')

@section('bread')
<li> <a href="{{ route('ventas.index') }}"> Ventas </a> </li>
@if($create)
<li> <a href="#"> Crear </a> </li>
@else
<li> {{ $venta->VtaNume }} </li>
@endif
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.min.css') }}">
@endsection

@section('js')
<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
<script src="{{ asset('plugins/select2/select2.js') }}"> </script>
<script src="{{ asset('plugins/onscan/onscan.min.js') }}"> </script>
<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
<script src="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('plugins/download/download.js') }}"> </script>  
<script type="text/javascript" src="{{ asset('js/numero_palabra.js')}}"></script>
<script type="text/javascript" src="{{ asset(mix('js/mix/ConectorPlugin.js'))}}"></script>
<script type="text/javascript" src="{{ asset(mix('js/mix/print_ticket.js'))}}"></script>
<script type="text/javascript" src="{{ asset('plugins/print/print.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset(mix('js/print_js/ConectorPlugin.js'))}}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset(mix('js/ventas/print_ticket.js'))}}"></script> --}}

<script type="text/javascript">

  window.decimales_dolares = {{ $decimales_dolares ?? 2 }};
  window.decimales_soles = {{ $decimales_soles ?? 2 }};

  var numStock = "{{ user_()->numAlmacenUsed() }}";
  var icbper_unit = 0.20;

  @if(isset($venta))
  var tipo_documento = "{{ $venta->TidCodi }}";
  var url_anular_documento = "{{ route('sunat.anular_documento') }}";
  @endif

  var canModifyPrecios = "{{ $canModifyPrecios }}";
  var disabledPrecioMin = "{{ $disabledPrecioMin ?? false}}";
  var accion_default = "edit";
  var ruc_crear = "{{ $ruc }}";
  var incluyeIGV = {{ $incluyeIgv }};  
  var venta_rapida = {{ $venta_rapida ?? 0 }};  
  var igvPorc = {{ $igvEmpresa->igvPorc }};
  var igvBaseCero = {{ $igvEmpresa->igvBaseCero }};
  var igvBaseUno = {{ $igvEmpresa->igvBaseUno }};
  var modulo_manejo_stock = {{ (int) $modulo_manejo_stock }};
  var modulo_restriccion_venta_por_stock = {{ (int) $modulo_restriccion_venta_por_stock }};

  $(document).ready(function() {
    window.id_factura = $("[name=codigo_venta]").val();
  });

  var url_crear_cliente = "{{ route('clientes.create') }}";
  var url_crear_cliente_simple = "{{ route('clientes.store_simple') }}";
  var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
  var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";
  var url_movimiento_producto = "{{ route('reportes.compra_venta', ['producto' => 'xxx']) }}";
  var url_actualizar_almacen_producto = "{{ route('productos.update_almacen', ['id' => 'xxx']) }}";

  var a;
  var url_buscar_cliente = "{{ route('clientes.consultar_datos') }}";
  var url_buscar_cliente_select2 = "{{ route('clientes.ventas.search') }}";
  var url_verificar_cliente = "{{ route('clientes.buscar_cliente') }}";
  var url_buscar_producto = "{{ route('productos.consultar_datos') }}";
  var url_buscar_producto_select2 = "{{route('productos.buscar_select2')}}";
  var url_buscar_producto_datos = "{{route('productos.consultar_alldatos')}}";
  var descuento_defecto = {{ $descuento_defecto }};

  var url_buscar_tipo_documento = "{{ route('ventas.tipo_documento_select') }}";
  var url_previsulizacion = "{{ route('ventas.prev') }}";
  
  var url_verificar_serie = "{{ route('ventas.verificar_serie') }}";
  var url_verificar_item_info = "{{ route('ventas.verificar_item') }}"
  var url_verificar_factura = "{{ route('ventas.verificar_factura') }}"
  var url_check_pago = "{{ route('ventas.check_pago') }}"
  var url_save_pago = "{{ route('ventas.save_pago') }}"
  var url_guia_salida = "{{ route('ventas.check_deudas') }}";
  var url_save_guiasalida = "{{ route('ventas.saveguia') }}"
  var url_listado_pago = "{{ route('ventas.index') }}";
  var url_route_clientes_consulta = "{{ route('clientes.consultaCliente') }}";
  var url_route_productos_consulta = "{{ route('productos.consulta') }}";
  var url_route_tipo_documento = "{{ route('ventas.tipo_documento') }}";
  var url_route_send_sunat = "{{ route('ventas.send_sunat') }}";
  var url_productos_vendidos = "{{ route('productos.vendidos') }}";
  var url_consulta_cotizacion = "{{ route('coti.importar') }}";
  var url_buscar_cotizaciones = "{{ route('coti.search') }}";
  var url_guardar_condicion = "{{ route('condicion.guardar_default') }}";
  var url_enviar_email = "{{ route('mail.enviar_documento') }}"
  var url_quitar_pago = "{{ route('ventas.quitar_pago') }}"
  var url_data_pago = "{{ route('ventas.data_pago') }}";
  let url_venta_data_impresion = "{{ route('ventas.data_impresion') }}";
  var url_verificar_ticket = "{{ route('ventas.verificar_ticket') }}"
  // datatables
  var table_clientes = null;
  var table_productos = null;
  var table_factura = null;
  
  // info
  var create = {{ (int) $create }};
  var table_tipodocumento = null;
  var table_numerofactura = null;
  var table_productos_vendidos = null;
  var table_cotizacion = null;
  var table_facturas = null;
  /* ----------------------------------------------- */
  var verificar_deudas = {{ $verificar_deudas }}
  var verificar_caja = {{ $verificar_caja }}
  var verificar_almacen = {{ $verificar_almacen }}
  var cursor_producto = {{ $cursor_pointer_producto }}
  var cursor_inicial = {{ $cursor_pointer_inicial }}
  var inicial_focus = {{ (int) $inicial_focus }}
  var impresion_default = {{ (int) $impresion_default }}

  @if($create)
  var ultimo_codigo_cliente = "{{ $ultimo_codigo }}";
  @endif
</script>

<script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
<script src="{{ asset(mix('js/clientes/mix/scripts.js')) }}"> </script>
<script src="{{ asset(mix('js/cajas/mix/pagos.js')) }}"> </script>
<script src="{{ asset(mix('js/ventas/mix/scripts.js')) }}"></script>
<script src="{{ asset(mix('js/ventas/mix/canje.js')) }}"></script>
@endsection

@section('titulo_pagina' )

@if( $create )
Crear nuevo documento
@else
Documento <span class="caja_number"> {{ $venta->VtaNume }} </span>
@endif



@endsection

@section('contenido')

  {{-- @dd( $notaCredito ) --}}

<div class="factura_div">
  @include('components.block_elemento')
  @include('ventas.partials.factura.botones')
  @include('ventas.partials.factura.nro_venta')
  @include('ventas.partials.factura.cliente')
  @include('ventas.partials.factura.info_principal')
  @include('ventas.partials.factura.moneda')
  @include('ventas.partials.factura.datos_referenciales')
  @if($create)
  @include('ventas.partials.factura.producto')
  @endif
</div>

@include('ventas.partials.factura.table_items')
@include('ventas.partials.factura.totales')

@if($create)

@include('ventas.partials.modal_productos', ['column_mov' => true ])

@if($modulo_canje_nv)
  @include('ventas.partials.modal_canje')
@endif

{{-- @include('ventas.partials.modal_seleccion_factura') --}}
@include('ventas.partials.modal_seleccion_tipodocumento')
@include('ventas.partials.modal_importacion')
@include('ventas.partials.modal_seleccion_cotizacion')
@endif

@include('ventas.partials.modal_confirmacion_guardado')

@if(get_option('OpcConta'))
@include('ventas.partials.modal_pago', ['tiposPagos' => $medios_pagos, 'showButtonsSalir' => !$venta_rapida ])
@include('ventas.partials.modal_pagos_comp')
@if( !$create )
@endif
@endif

@if($verificar_deudas)
@include('ventas.partials.modal_deudas')
@endif

@if( $verificar_almacen )
@include('ventas.partials.modal_guiasalida', ['tipoVenta' => true, 'showButtons' => false ])
@endif

@include('ventas.partials.modal_sunat_confirmacion')
@include('ventas.partials.modal_sunat_respuesta')
@include('ventas.partials.modal_productos_vendidos')

@include('ventas.partials.modal_condicion_venta' , [
'condicion' => $condicion,
'tipo_condicion' => "Venta",
'hide_label' => true,
'condicion_cot' => $condicion_cot
])

<!-- condicion_cot -->

@if( $create )
@include('ventas.partials.modal_calculo')
@include('ventas.partials.modal_asociar_guias')
@else
@if( $has_guias_asoc )
@includeIf('ventas.partials.modal_asociar_guias', [ 'guias' => $guias ])
@endif
@endif

@if($create)
@include('clientes.partials.modal_clientes_proveedores')
@include('guia_remision.partials.modal_despacho', [ 'guia' => $guia ])
@endif

@include('ventas.partials.modal_forma_pagos')


{{-- Modal para poner información --}}


@endsection