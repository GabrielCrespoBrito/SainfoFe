@extends('layouts.master')

@section('title', 'Home')

@section('js')  
  <script type="text/javascript">
    var url_data_mes = "{{ route('reportes.importe_mensual') }}"
    window.data_mes = {!! json_encode($data_mes) !!}
  </script>
  <script type="text/javascript" src="{{ asset('plugins/chart/chart.js') }}"></script>
  <script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}">  </script>
  <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('titulo_pagina', 'Home')

@section('contenido')



<div class="row dashboard-botones">
  <div class="col-md-6 col-md-offset-6 col-sm-12 col-xs-12 text-right"> 
    @include('components.specific.select_mes',  ['class_adicional' => 'mes'])
  </div>
</div>

<div class="row dashboard">

  <div class="col-lg-2  col-xs-6">
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
    @include('components.div_dashboard_data', [
      'isGuia' => true,
      'codigo' => '09' , 
      'nombre' => 
      'Guias de Remisiòn', 
    ])
  </div> 

</div>

<div class="row">
  <div  class="col-md-6">
    <div class="title_grafica">
      Ventas del Mes <span class="total">{{ $data_grafica['total'] }}</span>  
    </div>
    <canvas height="100px" id="canvas_importe"></canvas>
  </div>

  <div  class="col-md-6">
    <div class="title_grafica">
      Compras de mes <span class="total">{{ $data_grafica['total'] }}</span>  
    </div>
    <canvas height="100px" id="canvas_importe2"></canvas>
  </div>

</div>

  <div id="root"></div>
@endsection