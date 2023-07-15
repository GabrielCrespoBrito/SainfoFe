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

@section('titulo_pagina', 'Utilidades ventas')
@section('contenido')

  <?php $class_adicional = "reportes"; ?>

  <form method="get" action="{{ route('reportes.util-ventas.pdf') }}" target="_blank">

  <div class="filtros">

  @include('reportes.partials.general.fechas')

  <!-- Articulo -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Tipo reporte</legend>

        <div class="row" id="demo">

          <div class="col-md-6" style="text-align: center;">
            <label class="radio-inline"><input name="tipo_reporte" value="resumen" type="radio" checked="checked"> Utilidad resumida  </label>
          </div>

          <div class="col-md-6" style="text-align: center;">
            <label class="radio-inline"><input name="tipo_reporte" value="detalle" type="radio"> Utilidad detallada  </label>

          </div>
        
        </div>                  

      </fieldset>
    </div>
  </div>
  <!-- Articulo --> 

  <div class="row">
    <div class="col-md-12">
      <input type="submit" name="Vista" class="btn btn-default">
    </div>
  </div>

  </div>

  </form>

@endsection