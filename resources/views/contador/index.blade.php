@extends('layouts.master_contador')

@php  
  $date = date('Y-m-d');
@endphp

@section('titulo_pagina' )
  <span class="ruc_c"> CONTADOR </span>
  <span class="nombre_c"> CONTADOR </span>
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
    var url_venta_consulta  = "{{ route('ventas.consulta') }}";
    var url_descargar_files = "{{ route('cliente_administracion.descargar_files') }}";    
  </script>
  <script src="{{ asset('js/contador/contador.js') }}"></script>

@endsection

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>  

  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>  

@endsection


@section('titulo_pagina', 'Ventas')

@section('contenido')

@include('components.block_elemento')

<div class="row warning">
  <div class="col-md-12">
    <span class="info"
    style="
color: #8a8a8a;
text-align: center;
display: block;
margin-bottom: 10px;
font-style: italic;"

    > No se pueden descargar mas de 500 documentos por vez.</span>
  </div>
</div>


<div class="row">

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
            'todos' => 'Todos',
            '01' => 'Factura', 
            '03' => 'Boleta', 
            '07' => "Nota Debito" ,
            '08' => 'Nota Debito' 
          ], 
          null , 
          [ 'class' => 'form-control input-sm' ])     
      !!} 
  </div>

  <div class="col-md-1 no_p">
    <input type="text" value="{{ $date }}"  name="fecha_desde" data-event="datepicker" class="form-control flat input-sm datepicker no_br text-center">
  </div>
  
  <div class="col-md-1 no_p">
    <input type="text" value="{{ $date }}" name="fecha_hasta"  data-event="datepicker" class="form-control flat input-sm datepicker no_br text-center">
  </div>


  <div class="col-md-1 no_pr">
    <button class="btn btn-block search_action btn-default btn-sm btn-flat"><span 
      class="fa fa-search"></span> Buscar </button>      
  </div>


  <div class="col-md-5 no_pl">
    <div class="section-button">
      <a href="#" class="btn btn-default btn-flat pull-right descargar_files" data-type="zip"> <span class="fa fa-file-zip-o"></span> Archivos </a>
      <a href="#" class="btn btn-default btn-flat pull-right descargar_files" data-type="excel"> <span class="fa fa-file-excel-o"></span> Excel </a>
    </div>
  </div>

</div>


  <table data-selected="true" data-multiple="true" style="width: 100% !important;" class="table sainfo-table sainfo-noicon oneline" id="datatable">
  <thead>
    <tr>
      <td class="nro_venta"> N° Venta </td>
      <td class="td"> T.D </td>
      <td class="doc"> N° Doc </td>    
      <td class="fecha"> Fecha </td>    
      <td class="clien3"> Cliente </td>    
      <td class="Moneda"> Mon </td>
      <td class="Estado text-center"> Estado </td>
    </tr>
  </thead>
  </table>


@endsection


