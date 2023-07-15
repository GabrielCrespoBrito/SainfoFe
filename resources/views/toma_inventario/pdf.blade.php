@extends('layouts.pdf.master')
@section('title_pagina', $nombre_reporte)

@section('content')

@component('components.reportes.reporte_basico.pdf', [
'nombre_reporte' => $nombre_reporte,
'ruc' => $ruc,
'nombre_empresa' => $nombre_empresa,
])

{{-- Filtros --}}
@slot('filtros')

<table class="table-header-informacion" width="100%">
  <tr>
    <td with="15%">
      <span class="bold">Local: </span> <span> {{ $local }}</span>
    </td>

    <td with="15%">
      <span class="bold">Fecha: </span> <span> {{ $fecha }}</span>
    </td>

    <td with="40%">
      <span class="bold">Nombre: </span> <span> {{ $nombre_toma }}</span>
    </td>

    <td with="30%">
      <span class="bold tipocambio">Estado: </span> <span> {{ $estado }}</span>
    </td>

  </tr>

  <tr>
    <td colspan="4" >
      <span class="bold">Observación: </span> <span> {{ $observacion }}</span>
    </td>
  </tr>
</table>
@endslot
{{-- /Filtros --}}


@slot('content')
  @include('toma_inventario.partials.pdf.table')
@endslot

@endcomponent
@endsection