@extends('layouts.master')

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/> 	
@endsection

@section('js')
	<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
	<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
	<script type="text/javascript">
	var url_crear_or_editar_usuario = "{{ route('usuarios.guardar_o_editar') }}";
	var last_code = "{{ $users->last()->usucodi }}";
    $(function(){
        $('#datatable').DataTable({
        'ordering'    : false,
        })
    });
	</script>
	<script src="{{ asset('js/usuarios/scripts.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Crear Usuario')

@section('contenido')

<form id="eliminar-user" action="#" style="display: none">
  @csrf	
	<input name="codigo">
</form>

<div class="acciones-div">
	<a href="{{ route('usuarios.mantenimiento') }}" class="btn btn-danger btn-flat pull-right">  Salir </a>
	<a href="#" class="btn btn-primary btn-flat pull-right"> <span class="fa fa-save"></span> Guardar </a>
</div>

@include('usuarios.partials.create_user')

@endsection