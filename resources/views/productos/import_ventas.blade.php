@extends('layouts.master')

@section('titulo_pagina', 'Importar ventas')
@section('js')
	<script type="text/javascript">
		var url_send = "{{ route('productos.import_store') }}";
	</script>
	<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('js/productos/importar.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/import/input_file.js') }}"></script>
@endsection

@section('contenido')
  @include('components.block_elemento')
  @include('import.ventas', ['venta' => 'venta'])
@endsection