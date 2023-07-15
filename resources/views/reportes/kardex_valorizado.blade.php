@extends('layouts.master')
<?php set_timezone(); ?>

@section('js')
  <script src="{{ asset('plugins/download/download.js') }}"> </script>       
  <script type="text/javascript">  
    var fecha_actual = '{{ date('Y-m-d') }}';    
    var url_search_producto = "{{ route('productos.buscar_select2') }}";
    var url_generate_report = "{{ route('reportes.kardex_pdf') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script type="module" src="{{ asset('js/reportes/kardex_valorizado.js') }}"></script>
@endsection

@section('titulo_pagina', 'Kardex Valorizado')
@section('contenido')

<?php $class_adicional = "reportes"; ?>

@include('reportes.partials.kardex_valorizado.filtros')
@include('reportes.partials.kardex_valorizado.botones')

@endsection