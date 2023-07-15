@extends('layouts.master')
@php
  $tipo = $tipo ?? 'I';
  $routeIndex = $routeIndex ?? '#';
  $despachoBtn = $tipo == 'S';
@endphp

@section('bread')
  <li> <a href="{{ $routeIndex }}"> {{ $titulo }} </a> </li>
  @if($create)
    <li> Crear </li>
  @else
    <li> {{ $venta->VtaNume }} </li>
  @endif
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.css') }}"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}"> <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.min.css') }}"> 
@endsection

@section('js')
	<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>    
	<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
	<script src="{{ asset('plugins/bootstrap-daterange/bootstrap-datepicker.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/numero_palabra.js')}}"></script>
  <script type="text/javascript">
    var accion_default = "edit";
    var accion = "{{ $accion }}";
    var ruc_crear = "{{ $ruc }}";
    var url_crear_cliente = "{{ route('clientes.create') }}";
    var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
    var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";
    // urls
    // producto_input_focus
      

    var url_guia_sendsunat = "{{  route('guia.sentsunat') }}"    
    var url_ubigeo = "{{ route('clientes.ubigeosearch') }}";
    var a;
    var url_buscar_cliente = "{{ route('clientes.consultar_datos') }}";
    var url_buscar_cliente_select2 = "{{ route('clientes.buscar_cliente_select2') }}";
    // var url_buscar_cliente_select2 = "{{ route('clientes.ventas.search') }}";
    var url_verificar_cliente = "{{ route('clientes.buscar_cliente') }}"; 
    var url_buscar_producto = "{{ route('productos.consultar_datos') }}";
    var url_buscar_producto_select2="{{route('productos.buscar_select2')}}";
    var url_buscar_producto_datos = "{{route('productos.consultar_alldatos')}}";
    var url_buscar_tipo_documento = "{{ route('ventas.tipo_documento_select') }}";  
    var url_verificar_serie = "{{ route('ventas.verificar_serie') }}";    
    var url_verificar_item_info = "{{ route('ventas.verificar_item') }}"
    var url_verificar_factura = "{{ route('ventas.verificar_factura') }}"
    var url_check_pago = "{{ route('ventas.check_pago') }}"

    var url_save_pago = "{{ route('ventas.save_pago') }}"
    
    var url_guia_salida = "{{ route('ventas.check_deudas') }}";

    var url_save_guiasalida  = "{{ route('ventas.saveguia') }}"

    var url_listado_pago = "{{ route('guia.index') }}"; 
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
    var modulo_manejo_stock = {{ $modulo_manejo_stock ?? 0 }}
     
    var url_data_pago = "{{ route('ventas.data_pago') }}"
    var url_verificar_ticket = "{{ route('ventas.verificar_ticket') }}"
		// datatables

    var url_guia_salida = "{{ $routeStore }}"
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
    var verificar_deudas = {{ $verificar_deudas }};
    var verificar_caja = {{ $verificar_caja }};
    var verificar_almacen = {{ $verificar_almacen }};
    @if($create)
      var ultimo_codigo_cliente = "{{ $ultimo_codigo }}";
    @endif
	</script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset(mix('js/clientes/mix/scripts.js')) }}"> </script>
	<script src="{{ asset('js/guia/scripts.js')}}"> </script>
@endsection

@section('titulo_pagina' , 'Crear ' . $titulo )

@section('contenido')
  <div class="factura_div">
    @include('components.block_elemento')
    @include('guia_remision.partials.factura.botones')
    @include('guia_remision.partials.factura.nro_venta')
    @include('guia_remision.partials.factura.cliente')
    @include('guia_remision.partials.factura.moneda')
    @if($create && $importar == false)
      @include('guia_remision.partials.factura.producto')
    @endif
  </div>

  @include('guia_remision.partials.factura.table_items' , [ 'importar' => $importar  ])
  @include('guia_remision.partials.factura.totales')
  
  @if(!$show)
    @include('guia_remision.partials.modal_productos')
    @include('guia_remision.partials.modal_seleccion_cotizacion')    
  @endif

  @if($create)
    @include('clientes.partials.modal_clientes_proveedores', ['defaultEntity' => 'P', 'closedTipo' => true, 'title' => 'Create Proveedor' ])  
  @endif

  @include('guia_remision.partials.modal_confirmacion', ['despachoBtn' => $despachoBtn])

@endsection