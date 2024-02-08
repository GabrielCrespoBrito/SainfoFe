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


@slot('content')

@include('reportes.vendedor.partials.zonas.table')


@endslot
@endcomponent

@endsection