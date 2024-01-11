@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">  
@endsection

@section('js')
  <script type="text/javascript">  
    var url_generate_report = "{{ route('reportes.productos_stock.report') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
@endsection

@section('titulo_pagina', 'Productos Stock Minimo')
@section('contenido')
@php  $class_adicional = "reportes"; @endphp
<div id="root-producto-stock" data-stockmin="0" data-route="{{ route('reportes.productos_stock_minimo.report') }}"></div>
@endsection