@extends('layouts.pdf.master')
@section('title_pagina', $nombre_reporte)

@section('js')
  <script>

    var vars = {};
    var x = window.location.search.substring(1).split('&');
    for ( var i in x ) {
      var z = x[i].split( '=' , 2 );
      vars[z[0]] = unescape(z[1]);
    }
    console.log({vars});
    document.getElementById('page').innerHTML = vars.page; 
  </script>

@endsection

@php
  $lastId = null;
@endphp

@section('content')

  @component('components.reportes.reporte_basico.pdf', [
    'nombre_reporte' => $nombre_reporte, 
    'nombre_local' => $nombre_local, 
    'nombre_lista' => $nombre_lista, 
    'ruc' => $ruc, 
    'nombre_empresa' => $nombre_empresa, 
    'tipo_cambio' => $tipo_cambio  
    ])

  @slot('filtros')

    {{-- Filtros --}}
    <table class="table-header-informacion" width="100%">
      <tr>
        <td with="30%">
          <span class="bold"> Local: </span> <span> {{ $nombre_local }} </span>
        </td>

        <td with="40%">
          <span class="bold"> Lista Precio: </span> <span> {{ $nombre_lista }} </span>
        </td>

        <td with="30%">
          <span class="bold tipocambio"> Tipo de Cambio: </span> <span> {{ $tipo_cambio }} </span>
        </td>      

      </tr>    
    </table> 
    {{-- /Filtros --}}

  @endslot
    
  
  @slot('content')
    @php
      $cantColumnas = $costo == 1 ? 12 : 9;
    @endphp

    <table class="table-contenido" width="100%">
      <thead>
        <tr> 
          <td> Nro Op.</td>
          <td> Codigo </td>
          <td> Unidad </td>
          <td> Descripcion </td>
          <td> Marca </td>
          <td style="text-align:right"> Peso </td>
          @if($costo == 1)
          <td style="text-align:right"> Costo US$ </td>
          <td style="text-align:right"> Costo S/ </td>
          <td style="text-align:right"> Margen US$  </td>
          @endif
          <td style="text-align:right"> Prec. US$ </td>
          <td style="text-align:right"> Prec. S/ </td>
          <td style="text-align:right"> Stock </td>
        </tr> 
      </thead>
      <tbody>
        @foreach ( $productos_group as $group )
          
          <tr class="tr-separador"> 
            <td> {{ $group->first()->GruCodi }} </td> 
            <td colspan="{{ $cantColumnas }}"> {{ $group->first()->GruNomb }} </td> 
          </tr>

        @foreach ( $group as $producto )
        @php
          if($producto->Id == $lastId){
            continue;
          }

          $lastId = $producto->Id;

        @endphp

        <tr>
          <td>{{ $producto->Id }} </td> 
          <td>{{ $producto->ProCodi }} </td> 
          <td>{{ $producto->UniAbre }} </td> 
          <td>{{ $producto->ProNomb }} </td> 
          <td>{{ $producto->MarNomb }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniPeso) }} </td> 
          @if($costo == 1)
          <td style="text-align:right">{{ fixedValue($producto->UniPUCD) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniPUCS) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniMarg) }} </td> 
          @endif
          <td style="text-align:right">{{ fixedValue($producto->UNIPUVD) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UNIPUVS) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->stock) }} </td> 
        </tr>
        @endforeach

        
        @endforeach
      </tbody>
      </table>

    @endslot
  @endcomponent

@endsection
