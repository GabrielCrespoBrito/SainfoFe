@extends('layouts.master')

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">  
@endsection

@section('js')
  <script src="{{ asset('plugins/download/download.js') }}"> </script>       
  <script src="{{ asset('plugins/select2/select2.js') }}"> </script>        
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
  <script src="{{ asset('js/reportes/kardex_fecha.js') }}"></script>
@endsection

@section('titulo_pagina', 'Kardex Traslado')
@section('contenido')

@php 
  $class_adicional = "reportes";
@endphp

@include('partials.errors_html')
@include('reportes.kardex_traslado.partials.filtros')

@endsection