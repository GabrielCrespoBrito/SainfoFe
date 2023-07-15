@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">  
@endsection

@section('js')
  <script src="{{ asset('plugins/download/download.js') }}"> </script>       
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>        
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript">  
    var fecha_actual = '{{ date('Y-m-d') }}';    
    var url_search_producto = "{{ route('productos.buscar_select2') }}";
    var url_generate_report = "{{ route('reportes.kardex_pdf') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/reportes/kardex_fisico.js') }}"></script>
@endsection

@section('titulo_pagina', 'Kardex Fisico')
@section('contenido')

<?php 
  $class_adicional = "reportes";
?>

@include('reportes.partials.kardex_fisico.botones')
@include('reportes.partials.kardex_fisico.filtros')


@endsection

