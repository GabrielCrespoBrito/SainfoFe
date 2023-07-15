@extends('layouts.master_cliente')
@php  
  $date = date('Y-m-d');
@endphp

@section('titulo_pagina' )
  <span class="ruc_c"> {{ $cliente->PCRucc }} </span>
  <span class="nombre_c"> {{ $cliente->PCNomb }} </span>
@endsection

@section('js')

  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>  
  <script src="{{ asset('plugins/download/download.js') }}"> </script>  
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"> 
  </script>

  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script type="text/javascript">
    var url_route_clientes_consulta = "{{ route('cliente_administracion.buscar_documentos') }}";
    var url_descargar_files = "{{ route('cliente_administracion.descargar_files') }}";
  </script>
  <script src="{{ asset('js/cliente_dashboard/cliente_dashboard.js') }}"></script>

@endsection

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>  

  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>  

@endsection


@section('titulo_pagina', 'Ventas')

@section('contenido')

@include('components.block_elemento');


<div class="row">

  <input type="hidden" name="filter" value="0">

  <div class="col-md-2 no_pr">
      {!!
        Form::select( 'estado' ,  
          [ 
            'todos' => 'Todos' ,
            '0' => 'Aceptado' ,  
            '9' => 'Pendiente' , 
            'anulado' => 'Anulado' , 
          ], 
          null , 
          [ 'class' => 'form-control input-sm' ])     
      !!} 
  </div>


  <div class="col-md-2">
      {!!
        Form::select( 'tipo' ,  
          [ 
            'todos' => 'Todos' ,
            '01' => 'Factura' , 
            '03' => 'Boleta' , 
            '07' => "Nota Debito" ,
            '08' => 'Nota Debito' 
          ], 
          null , 
          [ 'class' => 'form-control input-sm' ])     
      !!} 
  </div>

  <div class="col-md-2  no_pr">
    <input type="text" value="{{ $date }}"  name="fecha_desde" data-event="datepicker" class="form-control flat input-sm datepicker no_br text-center">
  </div>
  
  <div class="col-md-2 no_pl">
    <input type="text" value="{{ $date }}" name="fecha_hasta"  data-event="datepicker" class="form-control flat input-sm datepicker no_br text-center">
  </div>


  <div class="col-md-2 no_p">
    <button class="btn btn-block search_action btn-default btn-sm btn-flat"><span 
      class="fa fa-search"></span> Buscar </button>      
  </div>


  <div class="col-md-2">
    <div class="section-button">
      <a href="#" class="btn btn-primary btn-flat pull-right descargar_files"> <span class="fa fa-cloud"></span> Descargar </a>
    </div>
  </div>

</div>

<div data-selected="true" data-multiple="true" class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table class="table v-t sainfo-table sainfo-noicon sainfo-table" id="datatable">
  <thead>
    <tr>
      <td> N° Doc </td>    
      <td> Fecha </td>    
      <td> TDoc. </td>          
      <td> Serie </td>          
      <td> Numero </td>          
      <td> Moneda </td>               
      <td> Importe </td>
      <td> Estado </td>
      <td> Descripción </td>
    </tr>
  </thead>
  </table>
</div>

@endsection


