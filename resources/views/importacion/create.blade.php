@extends('layouts.master')

@section('titulo_pagina', $titulo )
@section('js')
	<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('js/productos/importar_new.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/import/input_file.js') }}"></script>

@endsection

@section('contenido')
  @include('components.block_elemento')
  <div id="errors-container" style="display:none"></div>
  @include('importacion.partials.form')
@endsection