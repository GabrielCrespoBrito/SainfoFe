@extends('layouts.master')

@section('titulo_pagina', 'Importar información')
@section('js')
	<script type="text/javascript">
		var url_send = "{{ route('productos.import_store') }}";
	</script>
	<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('js/productos/importar.js') }}"></script>
@endsection

@section('contenido')
  @include('components.block_elemento')
  @include('productos.partials.form_import')
@endsection