@extends('layouts.master')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}" />
@endsection

@section('js')
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript">
    var accion = 'create';
    var tipo_movimiento = '{{ $tipo_movimiento }}';
    var url_search = "{{ route('cajas.search_movimientos', ['caja_id' => $caja->CajNume, 'id_tipomovimiento' => $tipo_movimiento ]) }}";    
    var url_factura = "{{ route('ventas.show', ['id_factura' => 'xxx' ]) }}";    
    var url_consultar =  "{{ route('cajas.consultar_movimiento' , [ 'id_caja' => $caja->CajNume , 'id_tipomovimiento' => $tipo_movimiento] ) }}"
    var url_crear = "{{ App\Caja::urlMovimientoAccion( 'crear' , $tipo_movimiento , $caja->CajNume ) }}";
    var url_modificar = "{{ App\Caja::urlMovimientoAccion( 'modificar' , $tipo_movimiento , $caja->CajNume ) }}";    
    var url_eliminar = "{{ App\Caja::urlMovimientoAccion( 'eliminar' , $tipo_movimiento , $caja->CajNume ) }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/cajas/dineroapertura.js') }}"> </script>  
  <script src="{{ asset('js/cajas/ingresos.js') }}"> </script>    
  <script src="{{ asset('js/cajas/egresos.js') }}"> </script>      
  <script src="{{ asset('js/cajas/movimientos.js') }}"> </script>

@endsection

@section('bread')
  <li><a href="{{ route('cajas.index') }}"> {{ $nombreCaja }}</a></li>
  <li><a href="{{ route('cajas.resumen' , $caja->CajNume ) }}"> {{ $caja->CajNume }}</a></li>  
  <li> Movimientos </li>  
@endsection

@section('titulo_pagina')
  {{ $nombreCaja }} <span class="caja_number">{{ $caja->CajNume }}</span> 
  <span class="caja_estado {{ $caja->CajEsta == "Ap" ? 'aperturada'  : 'cerrada' }}"> 
    {{ $caja->CajEsta == "Ap" ? "Aperturada" : "Cerrada" }}
  </span>
@endsection

@section('contenido')
  @include('cajas.partials.movimientos.botones')
  @include('cajas.partials.movimientos.totales')
  @include('cajas.partials.movimientos.table_datatable')
  @include('cajas.partials.modal' , ['tipo_movimiento' => $tipo_movimiento  ] )
  @include('cajas.partials.modal_caja' , [ 'url' => route('cajas.dinero_apertura', $caja->CajNume) ] )
  @if($is_ingreso )
    @include('cajas.partials.modal_ingreso' , [ 'url' => route('cajas.dinero_apertura', $caja->CajNume) ] )
  @else
    @include('cajas.partials.modal_egreso' , [ 'url' => route('cajas.dinero_apertura', $caja->CajNume) ] )  
  @endif

@endsection


