@extends('layouts.master')

@php 
  set_timezone(); 
@endphp

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/
	dataTables.bootstrap.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.min.css') }}"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}"> 
@endsection

@section('js')
	<script src="{{asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>	
	<script src="{{asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>  
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/numero_palabra.js')}}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterange/locales/bootstrap-datepicker.es.js') }}"></script>
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>
	<script type="text/javascript">
    var accion_default = "edit";		
    var ruc_crear = "{{ $ruc }}";    
		var a;
    var is_orden = {{  (int) ($is_orden ?? 0) }};
    window.decimales_dolares = {{ $decimales_dolares ?? 2 }};
    window.decimales_soles = {{ $decimales_soles ?? 2 }};
    var canModifyPrecios = {{ $canModifyPrecios }}
    var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
    var url_crear_cliente = "{{ route('clientes.create') }}";		
    var url_buscar_cliente_select2 = "{{ route('clientes.buscar_cliente_select2') }}";
		var url_buscar_cliente        = "{{ route('clientes.consultar_datos') }}";
		var url_verificar_cliente     = "{{ route('clientes.buscar_cliente') }}";	
		var url_buscar_producto       = "{{ route('productos.consultar_datos') }}";
		var url_buscar_producto_datos = "{{ route('productos.consultar_alldatos') }}";
		var url_buscar_tipo_documento = "{{ route('ventas.tipo_documento_select') }}";	
		var url_verificar_item_info     = "{{ route('ventas.verificar_item') }}"		
		var url_route_clientes_consulta = "{{ route('clientes.consultaCliente') }}";
		var url_route_productos_consulta= "{{ route('productos.consulta') }}";
		var url_guardar_cotizacion      = "{{ route('coti.save') }}";
		var url_liberar = "{{ route('coti.liberar') }}";
		var url_listado_cotizaciones = "{{ route('coti.index', ['tipo' => $tipo ]) }}";
		var url_guardar_condicion = "{{ route('condicion.guardar_default') }}";
		var url_editar_cotizacion = "{{ route('coti.update') }}"
    var modulo_manejo_stock = {{ (int) $modulo_manejo_stock }};
    var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";		
		var table_clientes = null;
		var table_productos = null;	
		var table_factura  = null;	
		var create = 1;
    var tipo = "{{ $tipo }}";
		var edicion = {{ (int) !$create }};
    var cursor_producto = {{ $cursor_pointer_producto }};
    var igvPorc = {{ $igvEmpresa->igvPorc }};
    var igvBaseCero = {{ $igvEmpresa->igvBaseCero }};
    var igvBaseUno = {{ $igvEmpresa->igvBaseUno }};
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script> 
  <script src="{{ asset(mix('js/clientes/mix/scripts.js')) }}"> </script>
	<script src="{{ asset(mix('js/cotizaciones/mix/crear_modificar.js')) }}"> </script>
	<script src="{{ asset(mix('js/ecommerce/mix/scripts.js')) }}"> </script>
@endsection

@section('bread')
  <li><a href="{{ $routeIndex }}"><i class="fa fa-dashboard"></i> {{ $nombre }}</a></li>
  @if($create)
  	<li> Nueva </li>
  @else
  	<li> {{ $cotizacion->CotNume }}  </li>
  @endif
@endsection

@section('titulo_pagina', $titulo)

@section('contenido')
  <?php $date = date('Y-m-d'); ?>
  
  <div class="factura_div">

    @if( $import )
      <input type="hidden" name="import_id" value="{{ $importInfo['id'] }}">
    @endif

    @include('cotizaciones.partials.factura.botones')
    @include('cotizaciones.partials.factura.mensajes')
    @include('cotizaciones.partials.factura.nro_venta')
    @include('cotizaciones.partials.factura.cliente')
    @include('cotizaciones.partials.factura.moneda')
    @include('cotizaciones.partials.factura.producto')
  
  </div>

  @include('cotizaciones.partials.factura.table_items')
  @include('cotizaciones.partials.factura.totales')
  @include('cotizaciones.partials.modal_clientes')
  @include('cotizaciones.partials.modal_ask_nuevo_cliente')
  @include('cotizaciones.partials.modal_productos')

  @include('ventas.partials.modal_condicion_venta' , [
    'condicion' => $condicion,
    'tipo_condicion' =>  $tipo_condicion,
    ])

  @if($create)


  @if($importHabilitado && $import)
    
    @component('components.modal', ['id' => 'modalImportTienda', 'title' => 'Tienda - Cotizaciones'])
      @slot('body')
        @include('ecommerce.partials.container') 
      @endslot
    @endcomponent

    {{--  --}}
    @component('components.modal', [
      'id' => 'modalShowOrden', 
      'title' => 'Cotización Tienda ' .  $importInfo['id'] 
    ])
      @slot('body')
          <div id="order-show-container"></div>
      @endslot
    @endcomponent

  @endif




    @php
      $ruc = '';
      $telf = '';
      $email = '';
      $razon_social = '';
      $clean = 1;
      if( $is_orden ){
        if( ! $orden_cliente['exist'] ){
          $razon_social = $orden_cliente['data']['razon_social'];
          $ruc = $orden_cliente['data']['documento'];
          $telf = $orden_cliente['data']['tlf'];
          $email = $orden_cliente['data']['email'];
          $clean = 0;
        }
      }
    @endphp
    @include('clientes.partials.modal_clientes_proveedores', [
      'ruc' => $ruc,
      'telf' => $telf,
      'email' => $email,
      'clean' => $clean,
      'razon_social' => $razon_social,
      'defaultEntity' => $cliente_entidad
      ])
  @endif

@include('ventas.partials.modal_condicion_venta' , [
'condicion' => $condicion,
'tipo_condicion' => "Venta",
'hide_label' => true,
'condicion_cot' => $condicion_cot
])

@endsection