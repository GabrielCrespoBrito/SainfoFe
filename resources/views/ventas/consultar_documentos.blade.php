@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
  <link href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}">
  <link href="https://cdn.datatables.net/autofill/2.3.1/css/autoFill.dataTables.min.css">    
@endsection

@section('js')
  <script src="{{asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script> 
  <script src="{{asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="https://cdn.datatables.net/autofill/2.3.1/js/dataTables.autoFill.min.js"></script> 
  
  <script type="text/javascript">
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_consultar = "{{ route('reportes.documentos_faltantes') }}";
    var url_buscar_producto_datos = "{{ route('productos.consultar_alldatos') }}";
    var url_route_productos_consulta = "{{ route('productos.consulta') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/ventas/consultar_documentos.js') }}"></script>
@endsection

@section('titulo_pagina', 'Consultar documentos faltantes')
@section('contenido')

  <?php $class_adicional = "reportes"; ?>
  @include('ventas.partials.consultar_documentos.botones_venta')
  @include('ventas.partials.consultar_documentos.resumen')
  @include('ventas.partials.consultar_documentos.filtros_venta')

@endsection


