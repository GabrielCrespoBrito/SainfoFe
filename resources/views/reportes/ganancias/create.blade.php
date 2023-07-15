@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />

@endsection

@section('js')
<script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
<script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
  var table;
  var fecha_actual = '{{ date('Y-m-d') }}';
  var url_reporte = "{{route('reportes.productos_mas_vendidos.pdf', ['fecha_hasta' => 'fecha_hasta', 'fecha_desde' => 'fecha_desde' , 'local' => 'local']) }}";

</script>
<script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
<script src="{{ asset('js/reportes/ganancias.js') }}"></script>
@endsection

@section('titulo_pagina', 'Utilidades de Ventas')
@section('contenido')

<?php 
  // Prototiping knew
  $class_adicional = "reportes";
?>

<form action="{{ route('reportes.utilidades.show') }}" id="form-reporte">

  @include('reportes.partials.productos_mas_vendidos.filtros')

  <div class="col-md-12 pl-0" style="margin-bottom:20px">
    <a href="#" class="generate-report btn btn-primary btn-flat"> Buscar </a> 
  </div>

</form>

<div id="div-table">
</div>


@endsection
