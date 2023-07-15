@extends('layouts.master')

@section('titulo_pagina', 'Importar Productos')
@section('js')
	<script type="text/javascript">
		var url_send = "{{ route('importar.productos.store') }}";
	</script>
	<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('js/productos/importar_new.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/import/input_file.js') }}"></script>

@endsection

@section('contenido')
  @include('components.block_elemento')
  @include('importacion.productos.form')
@endsection