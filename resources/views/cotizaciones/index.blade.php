{{-- @dd( $routes ) --}}

@extends('layouts.master')
<?php set_timezone(); ?>
@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/tagsinput/tagsinput.css') }}"/>     
  <style>
    #ui-datepicker-div {display: none;}
  </style>
@endsection



@section('bread')
  <li> {{ $titulo_pagina }} </li>
@endsection

@section('js')
  <script type="text/javascript">
    var active_or_disable_;
    var active_ordisable_tr;
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_consulta  = "{{ route('coti.search') }}";
    var url_editar =  "{{ $routes->edit }}";
    var url_mail = "{{ route('mail.enviados') }}";
    var url_send_email = "{{ route('mail.cotizacion_redactada') }}";
  </script>
  <script src="{{ asset('plugins/tagsinput/tagsinput.js') }}"> </script>  
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset('js/cotizaciones/index.js') }}"> </script>  
@endsection

@section('titulo_pagina', $titulo_pagina)

@section('contenido')

<div class="row">

  <div class="col-md-2 no_pr">                
    @component('components.specific.select_mes')
    @endcomponent
  </div>

    <div class="col-md-2 col-sm-4  col-xs-6">
      <select name="local" class="form-control input-sm">
        @foreach ($locales as $local)
        <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>Local - {{ optional($local->local)->LocNomb }} </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2 col-sm-4  col-xs-6">
      <select name="vendedor" class="form-control input-sm">
        <option value=""> - VENDEDORES - </option>
        @foreach ($vendedores as $vendedor)
        <option value="{{ $vendedor->Vencodi }}"> {{ $vendedor->vennomb }} </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2 col-sm-4  col-xs-6">
      <select name="estado" class="form-control input-sm">
        <option value=""> - ESTADO - </option>
        <option value="P">Pendiente</option>
        <option value="F">Facturado</option>
        <option value="A">Anulado</option>
      </select>
    </div>


  <input type="hidden" name="tipo" value="{{ $tipo }}">

  <div class="col-md-offset-2 col-md-2 acciones-div ww">
    {{-- <a href="{{    route('coti.create' , [ 'tipo' => $tipo ]) }}" data-toggle="tooltip" title="Nueva" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> Nueva </a> --}}
    <a href="{{ $routes->create }}" data-toggle="tooltip" title="Nueva" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> Nueva </a>
  </div>

</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table style="width: 100% !important;" class="table sainfo-table sainfo-noicon oneline" id="datatable">
  <thead>
    <tr>
      <td width="100px">N° Doc. </td>
      <td width="80px"> Fecha </td>
      <td width="190px">Cliente </td>
      <td width="120px">Usuario </td>
      <td width="30px"> Mon </td>
      <td width="50px"> Val Vta </td>
      <td width="50px"> IGV </td>
      <td width="50px"> Importe</td>
      <td width="40px"> Estado </td>
      <td width="40px"> Doc. </td>
      <td class="acciones"> Acciones</td>      
    </tr>
  </thead>
  </table>
</div>

@include('partials.modal_eliminate', ['url' => route('coti.delete' , 'XX') ])
@include('cotizaciones.partials.modal_redactar_correo')

@endsection

@section('js')
  @include('partials.errores')
@endsection