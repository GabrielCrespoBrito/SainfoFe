@extends('layouts.master')

@section('title', 'Home')

@section('js')  
  <script type="text/javascript">
    var url_data_mes = "{{ route('reportes.importe_mensual') }}"
  </script>
  <script type="text/javascript" src="{{ asset('plugins/chart/chart4.js') }}"></script>
  {{-- <script type="text/javascript" src="{{ asset('plugins/chart/chart.js') }}"></script> --}}
  <script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}">  </script>
  <script type="text/javascript" src="{{ asset(mix('js/mix/dashboard.js')) }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('titulo_pagina', 'Home')

@section('contenido')
<div class="row dashboard-botones">
  <div class="col-md-6">
    @include('partials.desarrollo-options')
  </div>

  <div class="col-md-6 col-sm-12 col-xs-12 text-right"> 
    @include('components.specific.select_mes',  ['class_adicional' => 'mes'])
  </div>
</div>



<div 
  id="data-dashboard"
  data-guiaEnviado="{{ route('guia.index' , ['format' => true, 'status' => '0', 'mes' => 'mes_' ]) }}"
  data-guiaPorEnviar="{{ route('guia.pendientes' , ['mes' => 'mes_']) }}"
  data-guiaNoAceptadas="#";  
  data-ventaEnviada="{{ route('ventas.index' , ['status' => '0001' , 'tipo' =>  'tipo_', 'mes' =>  'mes_' ]) }}"
  data-ventaPorEnviar= "{{ route('ventas.index' , ['status' => '0011' , 'tipo' => 'tipo_', 'mes' => 'mes_' ]) }}"
  data-ventaNoAceptadas= "{{ route('ventas.index' , ['status' => '0002' , 'tipo' => 'tipo_' , 'mes' =>  'mes_' ]) }}"
  class="row dashboard">
</div> 



{{--  --}}
<div class="row">

  <div class="col-md-4 col-md-offset-2">
      <div class="container-graphic-status">
        <canvas id="graphic-status"></canvas>
      </div>
  </div>

  <div class="col-md-4">
      <div class="container-graphic-ussage">
          <canvas height="50px" id="graphic-ussage"></canvas>
      </div>
      <div id="legend"></div>
  </div>

</div>

<hr> 
{{--  --}}

{{--  --}}
<div class="row">
  @include('dashboard.partials.graphic', ['tipo' => 'ventas' ])
  @include('dashboard.partials.graphic', ['tipo' => 'compras' ])
</div>
{{--  --}}

  <div id="root"></div>
@endsection