@extends('layouts.master')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
@endsection

@section('bread')
  <li> Banco </li>  
@endsection


@section('js')
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>  
  <script type="text/javascript">
    var url_consulta  = "{{ route('banco.search')  }}";
    var url_eliminar  = "{{ route('banco.destroy')  }}";    
    var url_apertura = "{{ route('banco.apertura')  }}";        
    var url_cerrar = "{{ route('banco.cerrar')  }}";
    var url_reaperturar = "{{ route('banco.reaperturar')  }}";            
  </script>
  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/bancos/index.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Banco')

<?php $class_adicional = "caja"; ?>


@section('contenido')

<div class="row">

<div class="col-md-8 text-right">  

  <div class="row">
    
  </div>  

  <div class="col-md-4 ventas no_pr">
    @component('components.specific.select_mes', ['name' => 'fecha' ]) @endcomponent
  </div>


  <div class="col-md-4 no_pr">
    <select class="form-control input-sm" name="bancos">      
      @foreach( $bancos as $banco )
        <option data-cuentas="{{ $banco }}" value="{{ $banco->first()->banco->bancodi }}">{{ $banco->first()->banco->bannomb }}</option>
      @endforeach
    </select>  
  </div>

  <div class="col-md-4 ventas">
    <select class="form-control input-sm" name="cuenta_id">
      @foreach( $bancos->first() as $key => $cuenta)
        <option {{ $loop->first ? 'selected=selected' : '' }} value="{{ $cuenta->CueCodi }}">{{ $cuenta->CueNume }}</option> 
      @endforeach
    </select>   
  </div>


</div>


<div class="col-md-4 acciones-div ww text-right">
  <a href="#" id="aperturar"  data-toggle="tooltip" title="Aperturar" class="btn btn-primary btn-flat"> <span class="fa fa-folder-open-o"></span>  Aperturar </a>

</div>

</div>


<!-- table v-t sainfo-table sainfo-noicon sainfo-table ventas-d table_ventas_index -->
<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">
  <table class="table sainfo-table" width="100%" id="datatable">
  <thead>
    <tr>
      <td> Numero </td>
      <td> Mes </td>
      <td> Apertura </td>
      <td> Cierre </td>
      <td> Saldo S/. </td>
      <td> Saldo US$ </td>
      <td> Estado </td>
      <td> Ult. Fecha Modf </td>
      <td> Usuario </td>
      <td> Acciones </td>
    </tr>
  </thead>
  </table>
</div>

@include('grupos.partials.modal_producto')


@endsection

