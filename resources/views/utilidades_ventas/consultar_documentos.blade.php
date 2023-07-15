@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
@endsection

@section('js')
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('plugins/download/download.js') }}"> </script>    
  
  <script type="text/javascript">
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_consultar = "{{ route('reportes.documentos_faltantes') }}";
    var url_buscar_rangos = "{{ route('reportes.buscar_rangos') }}";    
    var url_generar_reporte = "{{ route('reportes.pdf_documentos_faltantes') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/ventas/consultar_documentos.js') }}"></script>
@endsection

@section('titulo_pagina', 'Consultar documentos faltantes')
@section('contenido')

  <?php $class_adicional = "reportes"; ?>
  @include('reportes.partials.consultar_documentos.botones_venta')
  @include('reportes.partials.consultar_documentos.resumen')
  @include('reportes.partials.consultar_documentos.filtros_venta')

@endsection


