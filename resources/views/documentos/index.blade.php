@extends('layouts.master')

<?php set_timezone(); ?>

@section('js')
@endsection

@section('titulo_pagina', 'Documentos pendientes')

@section('contenido')

<?php 

$factura = App\NotificacionDocumentosPendientes::FACTURA;
$boleta = App\NotificacionDocumentosPendientes::BOLETA;
$todo = App\NotificacionDocumentosPendientes::LAPSO_TODO;
$vencer = App\NotificacionDocumentosPendientes::LAPSO_VENCER;

 ?>

<div class="row">
  <div class="col-md-12 descripcion {{ $lapso == $vencer ? $vencer  : '' }}" style="font-size: 1.1em;background-clip: content-box;">
      <span class="texto"> {{ strtoupper($tipo_documento) }} pendientes por enviar </span>
      @if($documentos)
        @if( $documentos->detalles->count() )
          <span class="" style="border: 1px solid #999; padding: 0 10px; margin: 0 10px" class="total"> {{ $documentos->cantidad }} </span>
          @php
            $route = $tipo_documento == 'boleta' ? route('boletas.agregar_boleta') : route('ventas.pendientes');
          @endphp
      

          <a href={{ $route }}#" target="_blank" class="pull-right btn btn-primary btn-sm"> Procesar estos documentos </a>
        @endif
      @endif
  </div>
</div>

<div class="row">

<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table style="width: 130% !important;" class="table sainfo-table sainfo-noicon" id="datatable">
  <thead>
    <tr>
      <td> N° Doc. </td>
      <td> Tipo documento </td>
      <td> Serie </td>
      <td> Numero </td>   
      <td> Fecha emisión </td>         
      <td> Importe total </td>
    </tr>    
  </thead>
  <tbody>
    <?php
      // dd($documentos->detalles);
    ?>

    @if($documentos)

    @if( $documentos->detalles->count() )    

    @foreach( $documentos->detalles as $docu )    
    <?php $venta = $docu->venta; ?>
      <tr>
        <td> {{ $venta->VtaOper }} </td>
        <td> {{ $venta->TidCodi }} </td>
        <td> {{ $venta->VtaSeri }} </td>
        <td> {{ $venta->VtaNumee }} </td>
        <td> {{ $venta->VtaFvta }} </td>        
        <td> {{ $venta->VtaImpo }} </td>                
      </tr>
    @endforeach

    @endif

    @else 
      <tr>
        <td class="text-center" colspan="6"> No ay documentos pendientes </td>
      </tr>

    @endif
  </tbody>
  </table>
</div>


@endsection

