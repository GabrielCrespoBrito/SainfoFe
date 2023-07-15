@extends('layouts.master')
<?php set_timezone(); ?>

@section('bread')
<li> Nube </li>
@endsection

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
@endsection

@section('js')
  <?php $class_adicional = "venta_index"; ?>
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script type="text/javascript">
    var table;
    var url_consulta  = "{{ route('nube.search_documentos') }}";
    var url_respaldar  = "{{ route('nube.respaldar_documento') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/nube/index.js') }}"> </script>  
@endsection

@section('titulo_pagina', 'Almacenamiento de archivos')

@section('contenido')


<div class="col-md-6 ventas">

  <div class="row">

    <div class="col-md-4">

    @include('components.btn_procesando')

    <select name="estado" class="form-control input-sm text-center">      
      <option selected value="all"> Todos </option>
      <option value="2"> Por respaldar  </option>
      <option value="1"> Con documentos faltantes </option>
      <option value="0"> Todo Respaldado </option>
    </select>

    </div>

    <input type="hidden" value="" name="hoy" class="form-control input-sm datepicker no_br text-center">  

  </div>

</div>

<div class="col-md-6 acciones-div ww">

  <a href="#" data-toggle="tooltip" title="Eliminar" class="btn btn-danger disabled btn-flat pull-right eliminar-accion"> <span class="fa fa-trash"></span>  </a>

  <a href="#"" data-toggle="tooltip" title="Subir" class="btn btn-primary btn-flat pull-right respaldar"> <span class="fa fa-cloud"></span> Subir </a>

</div>

<div>  

  @include('components.block_elemento')

  @component('components.table' , ['id' => 'datatable' , 'class_name' => 'nube_table',  'thead' => ['N° Venta', 'Documento' , 'XML' , 'PDF' , 'CDR' , 'Estado' ] ])

  @endcomponent   

</div>


@endsection

