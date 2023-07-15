@php
$isVenta = $isVenta ?? true;
$hideEstado = $isVenta ? false : true;
$colSize = $hideEstado ? 6 : 4;
$title = $isVenta ? 'Reporte mensual de ventas' : 'Reporte mensual de compras' ;
$route = $isVenta ? route('reportes.ventas_mensual_pdf') : route('reportes.compras_mensual.pdf')
@endphp

@extends('layouts.master')

<?php
$formato = $formato ?? null;
$mes = $mes ?? null;
$estado_sunat = $estado_sunat ?? null;
set_timezone();
?>

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
@endsection

@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $(".buscar").on('click', function() {
      let url = $("[name=year] option:selected").attr('data-link');
      $(this).attr('href', url);
    })
  });
</script>
@endsection

@section('titulo_pagina', $title )

@section('contenido')

@php $class_adicional = "reportes"; @endphp

<form method="get" action="{{ $route }}" target="_blank">

  <!-- Fechas -->
  <div class="row">

    {{-- Mes --}}
    <div class="col-md-{{$colSize}} no_pr">

      <div class="filtro" id="condicion">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Mes </legend>
          <div class="row" id="demo">
            <div class="col-md-12">
              @component('components.specific.select_mes', ['mes' => $mes]) @endcomponent
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    {{-- Mes --}}

    @if(!$hideEstado)
    {{-- Estado Sunat --}}
    <div class="col-md-{{$colSize}} no_p">

      <div class="filtro" id="condicion">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Estado Sunat </legend>
          <div class="row" id="demo">
            {{-- Estado sunat --}}
            <div class="col-md-12">
              <select class="form-control input-sm" name="estado_sunat">
                <option value="todos" {{ $estado_sunat == 'todos' ? 'selected=selected' : '' }}> Todos </option>
                <option value="0001" {{ $estado_sunat == '0001' ? 'selected=selected' : '' }}> Aceptados </option>
                <option value="0002" {{ $estado_sunat == '0002' ? 'selected=selected' : '' }}> Rechazados </option>
                <option value="0003" {{ $estado_sunat == '0003' ? 'selected=selected' : '' }}> Anulado </option>
                <option value="0011" {{ $estado_sunat == '0011' ? 'selected=selected' : '' }}> No se encuentra </option>
              </select>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    {{-- Estado Sunat --}}
    @endif


    {{-- Mes --}}
    <div class="col-md-{{$colSize}} no_pl">
      <div class="filtro" id="condicion">
        <fieldset class="fsStyle">
          <legend class="legendStyle"> Formato Reporte </legend>
          <div class="row" id="demo">
            {{-- Formato --}}
            <div class="col-md-12">
              <select class="form-control input-sm" name="formato">
                <option value="html" {{ $formato == 'html' ? 'selected=selected' : '' }}> EN LINEA </option>
                <option value="pdf" {{ $formato == 'pdf' ? 'selected=selected' : '' }}> PDF </option>
                <option value="excell" {{ $formato == 'excell' ? 'selected=selected' : '' }}> EXCELL </option>
              </select>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    {{-- Mes --}}


  </div>
  <!-- Fechas -->



  {{-- estado_sunat --}}

  <div class="row">

    <div class="col-md-12 text-center">
      <label class="pull-left" for="cierre_mes" style="margin-left:20px" data-toggle="tooltip" title="Al Seleccionar, se cerrada el mes del Reporte y por tanto ya no podra emitir o anular documentos de dicho mes. Seleccione solo cuando ya no tenga documento que emitir de dicho Mes">
        <input type="checkbox" id="cierre_mes"  name="cerrar_mes" value="1"> Cerrar Mes
      </label>

      <input type="submit" name="Vista" value="Generar Reporte" class="btn btn-primary">
    </div>
  </div>

</form>

@if( isset($data_reporte) )

<div class="row" id="div_info_reporte">
  <div class="col-md-12" style="overflow-x: scroll;">
    @if( count($data_reporte['ventas_group']) )
    <h3 class="title_reporte"> Información Reporte </h3>
    @include('reportes.ventas_mensual.partials.table_new' , ['ventas_group' => $data_reporte['ventas_group'] , 'total' => $data_reporte['total'] ])
    @else
    <h3 class="title_reporte"> No hay información del mes seleccionado </h3>
    @endif
  </div>
</div>

@endif

@endsection