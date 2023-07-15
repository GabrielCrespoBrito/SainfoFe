@extends('layouts.pdf.master')
@section('title_pagina', $nombre_reporte)

@section('js')
@endsection

@section('content')

@component('components.reportes.reporte_basico.pdf', [
'nombre_reporte' => $nombre_reporte,
'ruc' => $ruc,
'nombre_empresa' => $nombre_empresa,
])

@php
  $caja_data = $caja_data ?? null;
@endphp

@slot('filtros')

{{-- Filtros --}}

<table class="table-header-informacion" width="100%">
  @if($fecha_desde)
  <tr>

    <td with="50%">
      <span class="bold"> Fecha desde: </span> <span> {{ $fecha_desde }} </span>
    </td>

    <td with="50%">
      <span class="bold"> Fecha hasta: </span> <span> {{ $fecha_desde }} </span>
    </td>
  </tr>
  @endif

  @if($caja_data)
  <tr>

    <td with="25%">
      <span class="bold"> Caja: </span> <span> {{ $caja_data->numero }} </span>
    </td>

    <td with="25%">
      <span class="bold"> Usuario: </span> <span> {{ $caja_data->usuario }} </span>
    </td>
    <td with="25%">
      <span class="bold"> Fecha Apertura: </span> <span> {{ $caja_data->fecha_apertura }} </span>
    </td>

    <td with="25%">
      <span class="bold"> Fecha Cierre: </span> <span> {{ $caja_data->fecha_cierre }} </span>
    </td>        
  </tr>
  @endif

</table>
{{-- /Filtros --}}

@endslot


@slot('content')
@foreach( $pagos_group as $key_pago => $pagos )
@php
$tipo_nombre_pago = App\TipoPago::find($key_pago)->getNombre();
@endphp
@include('reportes.ventas_tipopago.partials.table', ['tipo_pago_nombre' => $tipo_nombre_pago, 'items' => $pagos ])
@endforeach

@endslot
@endcomponent

@endsection