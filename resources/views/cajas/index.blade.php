@extends('layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}" />
@endsection

@section('bread')
<li> Cajas </li>
@endsection

@section('js')
<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
<script type="text/javascript">
  var active_or_disable_;
  var active_ordisable_tr;
  var table;
  var accion = "create";
  var url_consulta = "{{ route('cajas.search')  }}";
  var url_eliminar = "{{ route('cajas.borrar')  }}";
  var url_aperturar = "{{ route('cajas.aperturar')  }}";
  var url_reaperturar = "{{ route('cajas.reaperturar')  }}";
  var url_cerrar = "{{ route('cajas.cerrar')  }}";
  var last_id = "{{ $last_id }}";
  var ay_apertura = "{{ $need_apertura }}";
</script>

<script src="{{ asset('js/cajas/index.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Cajas')

<?php $class_adicional = "caja"; ?>


@section('contenido')


<div class="row">

  <div class="col-md-8 text-right">

    <div class="row">

    </div>
    @php
    $user_loccodi = auth()->user()->local();
    @endphp


    <div class="col-md-4 ventas no_pr">
      @component('components.specific.select_mes', ['name' => 'fecha' ]) @endcomponent
    </div>


    <div class="col-md-4 no_pr">
      <select name="local" class="form-control input-sm text-center">
        @foreach( $locales as $local )
        <option {{ $user_loccodi == $local->LocCodi ? 'selected=selected' : '' }} value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
        @endforeach
      </select>
    </div>


    @if(!$caja_local)
    <div class="col-md-4 ventas">
      <select name="usuario" class="form-control input-sm text-center">
        @foreach( $usuarios as $usuario )
        <option value="{{ $usuario->user->usulogi }}" {{ $usuario->user->usulogi == auth()->user()->usulogi ? 'selected=selected' : '' }}> {{ $usuario->user->usulogi }} </option>
        @endforeach
      </select>
    </div>
    @endif


  </div>


  <div class="col-md-4 acciones-div ww text-right">
    <a href="#" id="aperturar" data-toggle="tooltip" title="Aperturar" class="btn btn-primary btn-flat"> <span class="fa fa-folder-open-o"></span>  Aperturar </a>
    <a href="#" id="cerrar" data-toggle="tooltip" title="Cerrar" class="btn btn-danger disabled btn-flat"> <span class="fa fa-folder-o"> </span> </a>
    <a href="#" target="_blank" data-href="{{ route('cajas.resumen', 'xxx') }}" id="resumen" data-toggle="tooltip" title="Resumen" class="btn btn-default disabled btn-flat"> <span class="fa fa-list-alt"></span> </a>

    <a href="#" target="_blank" data-href="{{ route('cajas.movimientos', ['id_caja' => 'xxx' , 'tipo_movimiento' => 'ingresos' ]) }}" id="movimiento" data-toggle="tooltip" title="Movimientos" class="btn btn-default disabled btn-flat"> <span class="fa fa-list"></span> </a>


    <a href="#" id="eliminar" data-toggle="tooltip" title="Eliminar" class="btn btn-danger disabled btn-flat"> <span class="fa fa-trash"> </span> </a>
  </div>

</div>


<!-- table v-t sainfo-table sainfo-noicon sainfo-table ventas-d table_ventas_index -->
<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">
  <table class="table sainfo-table" width="100%" id="datatable">
    <thead>
      <tr>
        <td> Numero </td>
        <td> Apertura </td>
        <td> Cierre </td>
        <td> Saldo S/. </td>
        <td> Saldo US$ </td>
        <td> Estado </td>
        <td> Ult. Fecha Modf </td>
        <td> Usuario </td>
      </tr>
    </thead>
  </table>
</div>

@include('grupos.partials.modal_producto')


@endsection