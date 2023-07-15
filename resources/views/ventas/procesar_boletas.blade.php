@extends('layouts.master')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
@endsection


@section('bread')
  <li><a href="{{ route('boletas.resumen_dia') }}"> Resumenes</a></li>
  <li>  Procesar </li>
@endsection

@section('js')
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript">
    var mes = 1;
    var table;
    @php 
      set_timezone();
      $route = $resumen ? route('boletas.update', [ 'docnume' => $resumen->DocNume,  'numoper' => $resumen->NumOper ]) : route('boletas.guardar_boletas');
    @endphp
    
    var fecha_actual = '{{ datePeru('Y-m-d') }}';
    var url_listado = "{{ route('boletas.resumen_dia') }}";
    var url_agregar_boletas = "{{ route('boletas.boletas_dia' ) }}";
    var url_guardar_boletas = "{{ $route }}";
    
    var url_enviar_resumen = "{{ route('boletas.enviar_resumen' ) }}";
    var url_validar_ticket = "{{ route('boletas.enviar_ticket' ) }}";
  </script>
  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>  
  <script src="{{ asset('js/ventas/resumen.js') }}"> </script>      
@endsection

@section('titulo_pagina')

@if($resumen)
  Procesar resumen <span class="caja_number"> {{ $resumen->DocNume }} </span>
@else
  Generar Resumen
@endif

@endsection

@section('contenido')
  @include('components.block_elemento')

<div class="row">
  
  <div class="col-md-9 acciones-div">

    <a href="#" data-toggle="tooltip" title="Guardar" class="pull-left btn btn-primary btn-flat pull-right guardar" data-home="{{ route('boletas.resumen_dia') }}"> <span class="fa fa-save"></span> Grabar </a>    

    @if( $resumen )


      @if($resumen->no_sido_enviado())
        <a href="#" class="pull-left btn btn-default btn-flat pull-right enviar_sunat"> Enviar Sunat </a>

        
        @if( auth()->user()->isAdmin() )
        {{-- <a href="{{ route('resumen.validar', $resumen->NumOper ) }}" class="pull-left btn btn-default btn-flat pull-right">Val</a>  --}}
        @endif

      @else
      @endif
    @endif

    <a href="{{ route('boletas.resumen_dia') }}" data-toggle="tooltip" title="Salir" class="pull-left btn btn-danger btn-flat pull-right salir"> <span class=""></span> Salir </a>


    
  </div> 

  <div class="col-md-3 text-right">
  @if($resumen)

    @if( $resumen->isSend() )
      <a href="#" class="btn btn-default btn-flat pull-right validar"> Validar </a>
    @endif


  <a href="{{ route('resumen.txt', $resumen->NumOper ) }}" class="btn btn-default btn-flat pull-right" data-toggle="tooltip" title="Generar .txt para consulta masiva en el sistema de la sunat"> Generar Txt </a>

    
  @endif

  @include('components.btn_procesando')

  </div>

</div>

<div class="row">
  <div class="col-md-12">
    @include('components.messages.alert', [ 'color' => 'message',  'message' => 'Aqui generaras el resumen de boletas que luego tendras que enviar a la sunat'])
  </div>
</div>

<?php  

?>

<div class="row">
  <div class="col-md-12 ventas">
    
    <input type="hidden" name="id_resumen" value="{{ $id_resumen }}">

    <div class="row">
      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Numero operación</label>
          <div class="col-sm-7">
            
            <input 
            class="form-control input-sm" 
            value="{{ $resumen ? $resumen->NumOper  : '' }}"  
            name="numero_operacion" 
            readonly
            type="text">

          </div>
        </div>
      </div>
      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5">Codigo operación</label>        
          <div class="col-sm-7">
            <input class="form-control input-sm" {{ $resumen ? '' : 'readonly=readonly' }} name="codigo_operacion" value="{{ $resumen ? $resumen->DocNume  : '' }}" type="text">
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Fecha de Generación</label>
          <div class="col-sm-7">
            <input class="form-control datepicker input-sm" value="{{ $resumen ? $resumen->DocFechaE  : datePeru('Y-m-d') }}"  {{ $resumen ? '' : 'readonly=readonly' }} name="fecha_generacion" type="text">
          </div>
        </div>
      </div>
      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Fecha de Envio</label>
          <div class="col-sm-7">
            <input class="form-control input-sm" value="{{ $resumen ? $resumen->DocFechaEv  : '' }}" readonly="readonly" name="fecha_envio" type="text">
          </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Fecha Documento</label>
          <div class="col-sm-7">
            <input class="form-control input-sm datepicker"   {{ $resumen ? '' : 'readonly=readonly' }} value="{{ $resumen ? $resumen->DocFechaD  : datePeru('Y-m-d') }}" name="fecha_documento" type="text">
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Número de TICKET</label>
          <div class="col-sm-7">
            <input class="form-control input-sm"  {{ $resumen ? '' : 'readonly=readonly' }} value="{{ $resumen ? $resumen->DocTicket  : '' }}" name="ticket" type="text">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-md-6">      
        <div class="form-group">
          <label class="col-sm-5 control-label">Moneda</label>
          <div class="col-sm-7">
            <input class="form-control input-sm" readonly="readonly" value="{{ $resumen ? $resumen->moneda->monabre  : 'Soles' }}" name="moneda" type="text">
          </div>
        </div>
      </div>
      <div class="col-md-6">      
        <div class="form-group">
          <label class="no_pl col-sm-5 control-label">Resumen operación</label>
          <div class="col-sm-7">
            <input class="form-control input-sm" readonly="readonly" title="{{ $resumen ? $resumen->DocDesc  : '' }}" value="{{ $resumen ? $resumen->DocDesc  : '' }}" name="resumen_operacion" type="text">
          </div>
        </div>
      </div>
    </div>    

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class=" col-sm-5 control-label">Estado</label>
          <div class="col-sm-7">
            <input class="form-control input-sm" readonly="readonly" name="estado" value="{{ $resumen ? $resumen->DocEstado  : '' }}" type="text">
          </div>
        </div>      
      </div>
    </div>

  </div>
</div>



<div class="row filtro">
  <div class="col-md-1"> Filtrar: </div>

  <div class="col-md-5">
     <input value="{{ $resumen ? $resumen->DocFechaE : datePeru('Y-m-d') }}" class="form-control datepicker input-sm" name="fecha_busqueda" type="text">
  </div>

  <div class="col-md-2">
    @if( $resumen )
      
    @else
      {!! Form::select( 'serie' , $series->pluck('sercodi', 'sercodi') , null , [ 'class' => 'form-control input-sm' ] ) !!}
    @endif
  </div>


  @if($resumen)
    @if($resumen->isSend())
    <div class="col-md-4">
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right quitar_boleta"> <span class="fa fa-trash"></span>  Quitar</a>
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right agregar_boleta"> <span class="fa fa-search"></span>  Agregar</a>
    </div>
    @endif
  @else 

    <div class="col-md-4">
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right quitar_boleta"> <span class="fa fa-trash"></span>  Quitar</a>
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right agregar_boleta"> <span class="fa fa-search"></span>  Agregar</a>
    </div>
        
  @endif
</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table class="table v-t sainfo-table sainfo-table table_boletas">
  <thead>
    <tr>
      <td class="nro_venta"> Item </td>
      <td class="td"> VtaOper </td>
      <td class="td"> T.D </td>
      <td class="serie"> Serie </td>        
      <td class="doc"> N° Doc </td>    
      <td class="doc"> T.D Enti </td>          
      <td class="fecha"> Codigo </td>    
      <td class="clien3"> NDoc.Ent </td>    
      <td class="clien3"> Estado </td>          
      <td class="Situación"> Gravada </td>
      <td class="Situación"> Exonerada </td>
      <td class="Situación"> Inafecta </td>
      <td class="Situación"> ICBPER </td>      
      <td class="Situación"> IGV </td>      
      <td class="Situación"> ISC </td>            
      <td class="Situación"> TOTAL </td>
    </tr>
  </thead>
  <tbody>    
    @if( $resumen )
    {{-- sortBy --}}
      @foreach( $resumen->items->sortBy('detNume'); as $item )
      @php          
        $doc = $item->documento();
      @endphp

        <tr class="{{ $item->VtaEsta == "A" ? 'boleta_anulada' : '' }}">
        <td>{{  $item->DetItem }} </td>
        <td> <a href="{{ route('ventas.show', $doc->VtaOper ) }}">  {{ $doc->VtaOper }} </a> </td>
        <td>{{  $item->tidcodi   }} </td>
        <td>{{  $item->detseri  }} </td>
        <td>{{  $item->detNume  }} </td>
        <td>{{  $item->TDocCodi }} </td>
        <td>{{  $item->PCCodi  }} </td>
        <td>{{  $item->PCRucc  }} </td>
        <td>{{  $item->VtaEsta  }} </td>
        <td>{{  $item->DetGrav  }} </td>
        <td>{{  $item->DetExon  }} </td>
        <td>{{  $item->DetInaf  }} </td>
        <td>{{  decimal($item->getBolsaTotal()) }} </td>
        <td>{{  $item->DetIGV }} </td>
        <td>{{  $item->DetISC  }} </td>
        <td>{{  $item->DetTota  }} </td>
        </tr>
      @endforeach
    @endif
  </tbody>
  </table>
</div>

@include('ventas.partials.modal_redactar_correo')

@endsection