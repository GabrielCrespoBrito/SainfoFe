@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   

@endsection

@section('js')
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

  <script type="text/javascript">
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_reporte = "{{ route('reportes.productos_mas_vendidos.pdf', ['fecha_hasta' => 'fecha_hasta', 'fecha_desde' => 'fecha_desde' , 'local' => 'locCodi']) }}";

  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/reportes/productos_mas_vendidos.js') }}"></script>
@endsection

@section('titulo_pagina', 'Productos mas vendidos')
@section('contenido')
  @include('partials.errors_html')
@php 
  $class_adicional = "reportes";
@endphp


<div class="filtros">
<form action="{{ route('reportes.productos_mas_vendidos.pdf') }}" method="post">
@csrf
	@include('reportes.partials.general.fechas')
	@include('reportes.partials.general.locales')
  <button type="submit" class="btn btn-flat btn-primary"> Guardar </button>
</form>

</div>
@endsection