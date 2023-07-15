@extends('layouts.master')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
@endsection

@section('bread')
<li> <a href="{{ route('ventas.index') }}"> Ventas </a> </li>
<li> Pendientes </li>

@endsection

@section('js')
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script type="text/javascript">
    var table;
    var url_venta_consulta = "{{ route('ventas.consulta_pendientes')}}";
    var url_enviar_sunat = "{{ route('sunat.verificar_pendientes') }}";
  </script>
  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>    
  <script src="{{ asset('js/ventas/pendientes.js') }}"> </script>  
@endsection

@section('titulo_pagina', 'Envio Documentos Pendientes')

@section('contenido')

<div class="row">

  <div class="col-md-4">

  @include('components.btn_procesando')
    <input type="hidden" value="{{ $hoy }}" name="hoy" class="form-control input-sm datepicker no_br text-center">  

    <select name="tipo" class="form-control input-sm text-center">
      <option {{ $tipo == 'todos' ? 'selected=selected' : '' }} value="todos"> Todos </option>
      <option {{ $tipo == 'factura' ? 'selected=selected' : '' }} value="01"> Factura </option>
      @if(is_ose())
      <option {{ $tipo == 'boleta' ? 'selected=selected' : '' }} value="03"> Boleta </option>
      @endif
      <option {{ $tipo == 'nota_credito' ? 'selected=selected' : '' }} value="07"> Nota Credito </option>
      <option {{ $tipo == 'nota_debito' ? 'selected=selected' : '' }} value="08"> Nota Dedito </option>  
    </select>

  </div>

  <div class="col-md-8 acciones-div">
      <a href="#" data-toggle="tooltip" title="Enviar seleccionados" class="btn btn-default btn-flat pull-right enviar-sunat"> <span class="fa fa-envelope"></span> Enviar </a>
      <a href="#" data-toggle="tooltip" title="Seleccionar todos seleccionados" id="select_all" class="btn btn-default btn-flat pull-right"> <span class="fa fa-users"> </span> </a>
  </div>

</div>


<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">

  @include('components.block_elemento')

  <table style="width: 100%;" class="table sainfo-table oneline" id="datatable">
  <thead>
    <tr>
      <td class="nro_venta"> N° Venta </td>
      <td class="td"> T.D </td>
      <td class="serie"> Serie </td>        
      <td class="doc"> N° Doc </td>    
      <td class="fecha"> Fecha </td>    
      <td class="clien3"> Cliente </td>    
      <td class="Moneda"> Moneda </td>
      <td class="Importe"> Importe </td>  
      <td class="XML"> XML </td>
      <td class="PDF"> PDF </td>
      <td class="CDR"> CDR </td>
      <td class="Situación"> Situación </td>
    </tr>
  </thead>
  </table>

</div>

@endsection