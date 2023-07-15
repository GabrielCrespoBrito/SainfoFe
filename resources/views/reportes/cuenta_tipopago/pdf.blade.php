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

  <tr>
    <td with="25%">
      <span class="bold"> CUENTA: </span> <span> {{ $caja_data->cuenta }} </span>
    </td>

    <td with="25%">
      <span class="bold"> BANCO: </span> <span> {{ $caja_data->banco }} </span>
    </td>
    <td with="25%">
      <span class="bold"> MONEDA: </span> <span> {{ $caja_data->moneda }} </span>
    </td>

    <td with="25%">
    </td>        

  </tr>

</table>
{{-- /Filtros --}}

@endslot


@slot('content')

@php
  $general_total_soles = 0; 
  $general_total_dolares = 0; 
@endphp

@foreach( $pagos_group as $key_pago => $items )



@php
$tipo_nombre_pago = App\TipoPago::find($key_pago)->getNombre();
@endphp

{{-- table  --}}

<p> <strong>{{ $tipo_nombre_pago }}</strong></p>
<table class="table-contenido table-pagos" width="100%">
  <thead>
    <tr>
      <td width="5%"> Nro Op. </td>
      <td width="7%"> F.Pago </td>
      <td width="8%"> Nª Doc </td>
      <td> Razòn Social </td>
      <td width="5%"> Voucher </td>
      <td width="5%"> Mon. </td>
      <td class="text-align-right" width="5%"> T.C </td>
      <td class="text-align-right" width="8%"> Importe </td>
    </tr>
  </thead>
  <tbody>
    @foreach( $items as $item )
    <tr class="vertical-align-top">
      <td> {{ $item->VtaOper }} </td>
      <td> {{ $item->PagFech }} </td>
      <td> {{ $item->PagBoch }} </td>
      <td> {{ $item->cliente->PCNomb }} </td>
      <td> {{ $item->VtaNume }} </td>
      <td> {{ $item->getMonedaNombre() }} </td>
      <td class="text-align-right"> {{ $item->PagTCam }} </td>
      <td class="text-align-right"> {{ $item->PagImpo }} </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    
    <tr class="total">
      <td colspan="4" class="text-align-right">
        <strong>TOTAL {{ $soles_abbre }} </strong> {{ decimal( $general_total_soles += $items->where('MonCodi','01')->sum('PagImpo')) }}
      </td>

      <td colspan="5" class="text-align-right">
        <strong>TOTAL {{ $dolar_abbre }} </strong> {{ decimal( $general_total_dolares +=  $items->where('MonCodi','02')->sum('PagImpo')) }}
      </td>
    </tr>


    @if( $loop->last )
    <tr class="total-general">
      <td colspan="4" class="text-align-right">
      <span style="float:left;font-weight:bold">TOTAL GENERAL</span>
        <strong>TOTAL {{ $soles_abbre }} </strong> s. {{ decimal($general_total_soles) }}
      </td>

      <td colspan="5" class="text-align-right">
        <strong>TOTAL {{ $dolar_abbre }} </strong> {{ decimal($general_total_dolares) }}
      </td>
    </tr>
    @endif

  </tfoot>
</table>

{{-- table  --}}



{{-- @include('reportes.cuenta_tipopago.partials.table', [
  'tipo_pago_nombre' => $tipo_nombre_pago,
  'items' => $pagos,
  'general_total_soles' => $general_total_soles,
  'general_total_dolares' => $general_total_dolares 
]) --}}
<br>
<br>
@endforeach

@endslot
@endcomponent

{{-- @dd($general_total_soles, $general_total_dolares) --}}

@endsection