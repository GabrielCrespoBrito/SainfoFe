@php
  $empresa = get_empresa();
  $nombre_reporte = "Reporte Facturación Electronica";
  $nombre_local = "bbbb";
  $nombre_empresa = $empresa->nombre();
  $ruc = $empresa->ruc();
@endphp

@extends('layouts.pdf.master')
@section('title_pagina', $nombre_reporte)
@section('content')

  @component('components.reportes.reporte_basico.pdf', [
    'nombre_reporte' => $nombre_reporte, 
    'ruc' => $ruc, 
    'nombre_empresa' => $nombre_empresa, 
    ])

  @slot('filtros')
    {{-- Filtros --}}
    <table class="table-header-informacion" width="100%">
      <tr>
        <td with="50%">
          <span class="bold"> Fecha inicio: </span> <span> {{ $fecha_inicio }} </span>
        </td>

        <td with="50%">
          <span class="bold"> Fecha final: </span> <span> {{ $fecha_final }} </span>
        </td>

      </tr>    
    </table> 
    {{-- /Filtros --}}
  @endslot
  
  @slot('content')
    <table class="table-contenido" width="100%">
      <thead>
        <tr>
          <td>#</td>
          <td>Serie-Numero</td>
          <td>Fecha Doc</td>
          <td>Ruc</td>
          <td>Razon Social</td>
          <td>Estado Sunat</td>
          <td>Descripcion</td>

        </tr>
      </thead>
      <tbody>
        @php
          $estados_sunat = [
            '0001' => 'El comprobante existe y está aceptado',
            '0002' => 'El comprobante existe  pero está rechazado',
            '0003' => 'El comprobante existe pero está de baja',
            '0011' => 'El comprobante de pago electrónico no existe',
          ];
        @endphp


        @foreach($datas as $data)
          <tr> 
            <td>{{ $data->GuiOper }}</td>
            <td>{{ $data->GuiSeri . '-' .  $data->GuiNumee }}</td>
            <td>{{ $data->GuiFemi }}</td>
            <td>{{ $data->PCRucc }}</td>
            <td>{{ $data->PCNomb }}</td>            
            
            @php
                $message = "";
                $code = "";

                if( $data->GuiEsta == "A" ){
                  $code = "0003";
                }
                else {
                  switch ($data->fe_rpta) {
                    case 0:
                    $code = "0001";
                    break;
                    case 9:
                    case "9":
                    $code = "0011";
                    break;
                    default:
                    $code = "0002";
                    break;
                  }
                }

                $message = $estados_sunat[$code];
            @endphp
            <td>{{ $code }}</td>
            <td>{{ $message }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @endslot
  @endcomponent
@endsection