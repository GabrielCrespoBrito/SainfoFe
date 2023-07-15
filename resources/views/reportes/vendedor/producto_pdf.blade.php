@extends('layouts.pdf.master')
@section('title_pagina', $info['reporte_nombre'])

@section('js')
@endsection

@section('content')

@component('components.reportes.reporte_basico.pdf', [
'nombre_reporte' => $info['reporte_nombre'],
'class_name' => "font-size-7",
'ruc' => $info['empresa_ruc'],
'nombre_empresa' => $info['empresa_nombre'],
])


@slot('filtros')
  @include('reportes.vendedor.partials.productos.header')
@endslot


@slot('content')

  @include('reportes.vendedor.partials.productos.table')
  
@endslot
@endcomponent

@endsection