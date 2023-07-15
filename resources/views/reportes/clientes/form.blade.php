@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
@endsection

@section('js')
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

  <script type="text/javascript">
    var url_reporte = "{{ route('reportes.mejores_clientes.pdf', ['fecha_hasta' => 'fecha_hasta', 'fecha_desde' => 'fecha_desde' , 'local' => 'local']) }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/reportes/productos_mas_vendidos.js') }}"></script>
@endsection

@section('titulo_pagina', 'Clientes')
@section('contenido')

  <?php $class_adicional = "reportes"; ?>

  <form method="get" action="{{ route('reportes.clientes_pdf') }}" target="_blank">

  <div class="filtros">

  @include('reportes.partials.general.fechas', ['onlyOne' => true ])

  <div class="row">
    <div class="col-md-12">
      <input type="submit" name="Vista" class="btn btn-default">
    </div>
  </div>

  </div>

  </form>

@endsection