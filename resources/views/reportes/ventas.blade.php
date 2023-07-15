@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
  <link href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">
  <link href="https://cdn.datatables.net/autofill/2.3.1/css/autoFill.dataTables.min.css">    
@endsection
{{-- reportes.ventas --}}
@section('js')
  <script src="{{asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script> 
  <script src="{{asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="https://cdn.datatables.net/autofill/2.3.1/js/dataTables.autoFill.min.js"></script> 
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>
  
  <script type="text/javascript">
    var table;
    var is_venta = "1"
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_buscar_producto = "{{ route('reportes.buscar_producto') }}";
    var url_buscar_producto_datos = "{{ route('productos.consultar_alldatos') }}";
    var url_route_productos_consulta = "{{ route('productos.consulta') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/mix/reporte.js') }}"></script>
  {{-- <script src="{{ asset('js/reportes/reporte.js') }}"></script> --}}
@endsection

@section('titulo_pagina', 'Ventas')
@section('contenido')


<?php 
  $class_adicional = "reportes";
?>

@include('reportes.partials.botones_venta')
@include('reportes.partials.filtros_venta')

@endsection
