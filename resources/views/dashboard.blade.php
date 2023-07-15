@extends('layouts.master')

@section('title', 'Home')

@section('js')  
  <script type="text/javascript">
    var url_data_mes = "{{ route('reportes.importe_mensual') }}"
  </script>
  <script type="text/javascript" src="{{ asset('plugins/chart/chart.js') }}"></script>
  <script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}">  </script>
  <script type="text/javascript" src="{{ asset(mix('js/mix/dashboard.js')) }}"></script>
  <script src="{{ asset('js/elegir_empresa/elegir_empresa.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('titulo_pagina', 'Home')

@section('contenido')
<div class="row dashboard-botones">
  <div class="col-md-6 col-md-offset-6 col-sm-12 col-xs-12 text-right"> 
    @include('components.specific.select_mes',  ['class_adicional' => 'mes'])
  </div>
</div>

{{--
  if( $isGuia ){
    $routeNameEnviado = route(  'guia.index' , ['format' => true , 'status' => '0', 'mes' => date('Ym') ]);
    $routeNamePorEnviar = route( 'guia.pendientes' , [ 'mes' => date('Ym') ]);
    $routeNameNoAceptadas = "#";
  }
  else {
    $routeNameEnviado = route( 'ventas.index' , ['status' => '0001' , 'tipo' => $codigo, 'mes' => date('Ym') ]);
    $routeNamePorEnviar = route( 'ventas.index' , ['status' => '0011' , 'tipo' => $codigo, 'mes' => date('Ym') ]);
    $routeNameNoAceptadas = route( 'ventas.index' , ['status' => '0002' , 'tipo' => $codigo , 'mes' => date('Ym') ]);
  }
--}}

<div 
  id="data-dashboard"
  data-guiaEnviado="{{ route('guia.index' , ['format' => true, 'status' => '0', 'mes' => 'mes_' ]) }}"
  data-guiaPorEnviar="{{ route('guia.pendientes' , ['mes' => 'mes_']) }}"
  data-guiaNoAceptadas="#";  
  data-ventaEnviada="{{ route('ventas.index' , ['status' => '0001' , 'tipo' =>  'tipo_', 'mes' =>  'mes_' ]) }}"
  data-ventaPorEnviar= "{{ route('ventas.index' , ['status' => '0011' , 'tipo' => 'tipo_', 'mes' => 'mes_' ]) }}"
  data-ventaNoAceptadas= "{{ route('ventas.index' , ['status' => '0002' , 'tipo' => 'tipo_' , 'mes' =>  'mes_' ]) }}"
  class="row dashboard">

  {{-- <div class="col-lg-2 col-xs-6">
    @include('components.div_dashboard_data', [ 'codigo' => '01' , 'nombre' => 'Facturas' ])
  </div>
  <div class="col-lg-2 col-xs-6">
    @include('components.div_dashboard_data', [ 'codigo' => '03' , 'nombre' => 'Boletas' ])
  </div>
  <div class="col-lg-2 col-xs-6">
    @include('components.div_dashboard_data', [ 'codigo' => '07' , 'nombre' => 'Notas de Credito'])
  </div>
  <div class="col-lg-2 col-xs-6">
    @include('components.div_dashboard_data', [ 'codigo' => '08' , 'nombre' => 'Notas de Debito'])
  </div>
  <div class="col-lg-2 col-xs-6">
    @include('components.div_dashboard_data', [ 'isGuia' => true, 'codigo' => '09' ,  'nombre' => 'Guias de Remisiòn' ])
  </div> --}}

</div> 

<div class="row">
  @include('dashboard.partials.graphic', ['tipo' => 'ventas' ])
  @include('dashboard.partials.graphic', ['tipo' => 'compras' ])
</div>

  <div id="root"></div>
@endsection