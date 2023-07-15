@extends('layouts.master')

@add_assets(["libs" => ['datepicker', 'select2'], 'js' => ['helpers.js','reportes/reporte.js'] ])
@endadd_assets

@section('title' , 'GUIA REMISIÓN REPORTE')

@section('js')
<script type="text/javascript">
  var is_venta = "0";
</script>

@endsection

@section('titulo_pagina', 'Guia Remisión Reporte')

@section('contenido')

@php $class_adicional = "reportes" @endphp

@include('reportes.partials.botones_venta', ['is_guia' => true ])
@include('reportes.partials.filtros_venta' ,['is_guia' => true ])


@endsection
