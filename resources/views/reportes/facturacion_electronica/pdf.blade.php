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
          <td style="text-align: center;" width="3%">TD</td>
          <td style="text-align: center;" width="8%">Correlativo</td>
          <td style="text-align: center;" width="8%">Fecha Doc</td>
          <td>Ruc</td>
          <td>Razon Social</td>
          <td>Estado Sunat</td>
          <td style="text-align: center;" witdth="35%">Descripcion</td>

        </tr>
      </thead>
      <tbody>
        @foreach($datas as $data)
          <tr> 
            <td>{{ $data->VtaOper }}</td>
            <td style="text-align: center;">{{ $data->TidCodi }}</td>
            <td style="text-align: center;">{{ $data->VtaNume }}</td>
            <td style="text-align: center;">{{ $data->VtaFvta }}</td>
            <td>{{ $data->cliente_with->PCRucc }}</td>
            <td>{{ $data->cliente_with->PCNomb }}</td>            
            <td>{{ $data->statusSunat->status_code }}</td>
            <td>{{ $data->statusSunat->status_message }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @endslot
  @endcomponent
@endsection



{{-- 
  
Hacerle el menor caso posible a la política. Ignorar noticias sobre Guaidó, Maduro, la MUD, Claudio Fermín, Henry Falcón, el PSUV y toda la comparsa de "analistas políticos" o economistas de twitter. El año pasado quedó demostrado que ninguno en ese grupo tiene idea de nada.

Una de mis hipótesis más elaboradas es que todo este juego de "un bando vs. el otro" genera bastante dinero. Pueden pasearse por los canales informativos de la diáspora en Miami y otros: el público necesita esta confrontación, y es un negocio perfecto, genera likes, seguidores, stories en redes sociales, pero en el fondo no nos hace ser más productivos. Y también es alimento para el bando progre, pues, "hay que defender todos los espacios democráticos informativos" y combatir la fake news.

Piensen en la reportera de VPI cuando entrevistaba a un ciudadano en la cola del black friday en el Sambil. No hallaba esa historia escandalosa de gente muriendo de hambre, ni de enchufados derrochando lujo, era un simple señor comprando unos zapatos a 10 $ que tenía que justificar por qué lo podía hacer en medio de la crisis. En ese hecho no hay un bando vs. el otro, ni el "esto antes era de una forma, pero ahora no se puede", creado como mecanismo de defensa de la diáspora para mantener su propia cámara de eco, cuando en verdad el país el año pasado cambió bastante.

Así que Fuck medios de comunicación. Fuck "analistas económicos". Fuck influencers. Es contraproducente estar "informado" de todo. En 2020 sólo leeré el precio del dólar, me asomo a ver como amanece mi cuadra y listo, todo lo demás es prescindible.
  
--}}