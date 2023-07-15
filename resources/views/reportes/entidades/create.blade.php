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

  <form action="{{ route('reportes.entidad_report') }}" method="post" target="_blank">
    @csrf

    <!-- Almacen -->
    <div class="filtro">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Tipo </legend>
          <div class="row" id="demo">
            <div class="col-md-12">
              <select type="text" requred name="tipo" class="form-control input-sm flat text-center">
                <option value="C"> Cliente </option>
                <option value="P"> Proveedor </option>
              </select>
            </div>
            </div>           
        </fieldset>
      </div>
    </div>
    <!-- Fechas -->

<div class="row">
  <div class="col col-md-12">
    <button type="submit" class="btn btn-primary btn-flat "> Generar Reporte </button>
    <a href="{{ route('home') }}" class="btn btn-danger btn-flat  pull-right"> Salir </a>
  </div>
</div>

</div>
</form>


@endsection