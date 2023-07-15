@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/> 
@endsection

@section('js')
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

  <script type="text/javascript">
    var url_reporte = "{{ route('reportes.mejores_clientes.pdf', ['fecha_hasta' => 'fecha_hasta', 'fecha_desde' => 'fecha_desde' , 'local' => 'LocCodi']) }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/reportes/productos_mas_vendidos.js') }}"></script>
@endsection

@section('titulo_pagina', 'Mejores Clientes')
@section('contenido')
  @include('partials.errors_html')

  <?php $class_adicional = "reportes"; ?>


<div class="filtros">
<form action="{{ route('reportes.mejores_clientes.pdf') }}" method="get">
@csrf
	@include('reportes.partials.general.fechas')
	@include('reportes.partials.general.locales')

  <div class="row">
  <div class="col-md-12">
   <button class="btn btn-flat btn-primary" type="submit"> Enviar </button>
  </div>	
  </div>	
</form>
</div>



  {{-- @include( 'reportes.partials.botones',['target' => '_blank']) --}}
  {{-- @include( 'reportes.partials.productos_mas_vendidos.filtros', ['route' => route('reportes.mejores_clientes.pdf') ] ) --}}

@endsection