@extends('layouts.master')

<?php set_timezone(); ?>

@section('bread')
<li>  Documentos respaldados </li>
@endsection

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/> 
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/tagsinput/tagsinput.css') }}"/><link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}
  "/> 
@endsection

@section('js')  
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"> 
  </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script type="text/javascript">
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_consulta  = "{{ route('documentos.search') }}";
    var url_upload  = "{{ route('documentos.upload') }}";
    var url_uploadSingle  = "{{ route('documentos.uploadSingle') }}";
  </script>  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>  
  <script src="{{ asset('js/documentos/index_upload.js') }}"> </script>  
@endsection

@section('titulo_pagina', 'Documentos subidos')

@section('contenido')


@include('components.block_elemento')

<div class="col-md-8 ventas">
  <div class="row">
    
    <div class="col-md-4 no_p">    
      <input type="hidden" style="text-indent: -44444444444px" value="0" name="buscar_por_fecha">
      <select name="tipo" class="form-control input-sm text-center">
        <option value="todos"> Todos </option>
        <option value="01"> Factura </option>
        <option value="03"> Boleta </option>    
        <option value="07"> Nota Debito </option>
        <option value="08"> Nota Credito </option>  
      </select>
    </div>

    <div class="col-md-3 no_p">    
      <input type="hidden" style="text-indent: -44444444444px" value="0" name="estatus">
      <select name="estatus" class="form-control input-sm text-center">
        <option value="todos"> Todos </option>
        <option value="1"> OK </option>
        <option value="0"> Faltantes </option>    
        <option value="2"> Sin subir </option>
      </select>
    </div>

    <div class="col-md-3 no_p">    
      <input type="hidden" style="text-indent: -44444444444px" value="0" name="mes">
      <select name="mes" class="form-control input-sm text-center">
        <option value="todos"> Todos </option>
        @foreach( $meses as $mes  )
        <option {{ $mes->actual() ? 'selected' : '' }}   value="{{ $mes->mescodi }}"> {{ $mes->mesnomb }} </option>
        @endforeach
      </select>
    </div>



  </div>

</div>

<div class="col-md-4 acciones-div ww">
  <a href="#" class="btn btn-primary btn-flat pull-right subir"> <span class="fa fa-cloud"></span>  Subir archivos </a>
</div>

@component('components.table' , [ 'id' => 'uploadTable', 'thead' => ["N° Venta","T.D","N° Doc","Fecha", "Cliente","XML","PDF","CDR", "Estado", ""]])
@endcomponent


@endsection