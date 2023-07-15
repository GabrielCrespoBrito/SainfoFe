@extends('layouts.master')
@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
@endsection
@section('js')
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script type="text/javascript">
  var url_consulta = "{{ route('empresa.search') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/empresa/index.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Mantenimiento de Empresas')

@section('contenido')

@push('js')
  @include('partials.errores')
@endpush

<form id="eliminar-user" action="#" style="display: none">
  @csrf 
  <input name="codigo" >
</form>

<div class="row">
  <div class="col-md-3">
    <div class="form-group">
      <select name="status" class="form-control" >
        <option value="1" selected> Activas </option>
        <option value="0"> Inactivas </option>
      </select>
    </div>  
  </div>    
    <div class="col-md-9">
    <div class="acciones-div">
      <a href="{{ route('empresa.create') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
    </div>
  </div>    
    
</div>

<table class="table table-bordered table-hover sainfo-table user-table" id="datatable" >
<thead>
  <tr>
    <td> Código </td>
    <td> Nombre </td>
    <td> RUC </td>
    <td> Estado </td>
    <td> Acciones </td>
  </tr>
</thead>
</table>

@include('usuarios.partials.modal_usuario')
@include('usuarios.partials.modal_eliminar_usuario')
@include('empresa.partials.modal_delete')


@endsection