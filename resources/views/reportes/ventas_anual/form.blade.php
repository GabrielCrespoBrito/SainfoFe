@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   

@endsection

@section('titulo_pagina', 'Ventas por año')

@section('contenido')

@php $class_adicional = "reportes"; @endphp


@include('reportes.ventas_anual.partials.filtros')

@endsection