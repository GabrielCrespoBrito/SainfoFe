@extends('layouts.master')
@section('bread')
  <li> <a href="{{ route('guia.index') }}"> Guias remisión </a> </li>
  @if($create)
    <li> <a href="#"> {{ $guia->numero() }} </a> </li>
  @else
    <li> {{ $guia->numero() }} </li>
  @endif
@endsection


@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>
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
    // var url_despacho = "{{ route('guia.despacho', $guia->GuiOper) }}";
    var url_despacho = "{{ $guia->getRouteDespacho() }}";
    var url_anular_documento = "{{ route('guia.anular') }}";
    // Estado para editar
    var estado_edit = "{{ $estado_edit }}";    
    var url_ubigeo = "{{ route('clientes.ubigeosearch') }}";    
    var url_buscar_cliente = "{{ route('clientes.consultar_datos') }}";
    var url_buscar_cliente_select2 = "{{ route('clientes.buscar_cliente_select2') }}";
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
    var url_save_guiasalida = "{{ route('ventas.saveguia') }}"
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
    var url_data_pago = "{{ route('ventas.data_pago') }}"
    var url_verificar_ticket = "{{ route('ventas.verificar_ticket') }}"
		// datatables
    var url_guia_sendsunat = "{{  route('guia.sentsunat' , $guia->GuiOper) }}"
    var url_guia_salida = "{{  route('guia.update' , $guia->GuiOper  ) }}"
    var modulo_manejo_stock = {{ (int) $modulo_manejo_stock }}
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
    var despachar = {{ $despachar ?? 0 }};
	</script>

  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/clientes/scripts.js') }}"> </script>
	<script src="{{ asset('js/guia/scripts.js') }}"> </script>
  @include('partials.errores')

@endsection

@section('titulo_pagina')
  <span class="caja_number">
    {{ $guia->numero() }}
  </span>
@endsection

@section('contenido')
  
  <div class="factura_div">
    @include('components.block_elemento')
    @include('guia_remision.partials.factura.botones')
    @include('guia_remision.partials.factura.nro_venta')
    @include('guia_remision.partials.factura.cliente', ['accion' => $accion ])
    @include('guia_remision.partials.factura.moneda')
    @if( $create )
      @include('guia_remision.partials.factura.producto')
    @endif
  </div>

  @include('guia_remision.partials.factura.table_items')
  @include('guia_remision.partials.factura.totales')
  @include('guia_remision.partials.modal_despacho')

  @if(! $show )
    @include('guia_remision.partials.modal_productos')
    @include('guia_remision.partials.modal_seleccion_cotizacion')    
  @endif

  @if( $create )
    @include('clientes.partials.modal_clientes_proveedores')  
  @endif

  @if( $guia->canModify() && !$guia->isGuiaTransportista() )
    @include('guia_remision.partials.factura.modal_generar_doc', [ 'overflow' => true ])    
  @endif

  @include('guia_remision.partials.modal_confirmacion') 

@if( ! $guia->haSidoTrasladada() )

  @php
    $tipo_movimiento = new App\TipoMovimiento();
    $tipo_movimientos = $tipo_movimiento->repository()->where('TmoInSa', 'I');
  @endphp

  @include('ventas.partials.modal_guiasalida', [
    'local_disabled' => $guia->Loccodi,
    'tipoVenta' => true,
    'showCancelBtn' => false,
    'guia_id' => $guia->GuiOper , 
    'showFecha' => true, 
    'showForm' => true, 
    'locales' => $almacenes, 
    'id' => 'modalTraslado', 
    'tipo_movimientos' => $tipo_movimientos 
  ])
    
@endif
@endsection