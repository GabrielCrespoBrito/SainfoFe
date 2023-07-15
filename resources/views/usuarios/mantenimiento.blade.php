@extends('layouts.master')

{{-- @section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/> 	
@endsection --}}

@add_assets(['libs' => ['datatable'] , 'js' => ['helpers.js','usuarios/index.js' ] ])
@endadd_assets

{{-- 

@section('js')
	<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
	<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
	<script type="text/javascript">
	var url_store = "{{ route('usuarios.store')   }}";
	var url_update = "{{ route('usuarios.update') }}";
	var url_consulta = "{{ route('usuarios.search') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/usuarios/index.js') }}"> </script>
  @include('partials.errores')
@endsection
 --}}

 
@section('titulo_pagina', 'Mantenimiento de usuarios')

@section('contenido')

<form id="eliminar-user" action="#" style="display: none">
	@csrf	
	<input name="codigo" >
</form>

<div class="acciones-div">
	<a href="#" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
</div>


<table data-url="{{ route('usuarios.search') }}" class="table table-bordered table-hover sainfo-table user-table" id="datatable" >
<thead>
	<tr>
		<td> Código </td>
		<td> Nombre </td>
		<td> Empresa </td>
    <td> Roles </td>    
    <td> Estado </td>
    <td> Acciones </td>
	</tr>
</thead>
</table>

@include('usuarios.partials.modal_usuario', ['roles' => $roles])
@include('usuarios.partials.modal_eliminar_usuario')

@endsection