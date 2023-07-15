@extends('layouts.master')

@section('bread')
<li> <a href="{{ route('guia.index') }}"> Guia </a> </li>
<li> Pendientes </li>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/popover/style.css') }}" />
@endsection

@section('js')
<script src="{{ asset('plugins/popover/script.js') }}"> </script>
<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
  var url_search = "{{ route('guia.search_pendientes') }}";
  var url_enviar_sunat = "{{ route('guia.sentsunat', '@@') }}";
  var url_sendcorre = "{{ route('guia.sent_email') }}";
</script>
<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
<script type="text/javascript" src="{{ asset('js/guia/pendientes.js') }}"></script>
@endsection

@section('titulo_pagina', 'Guias Pendientes')

@section('contenido')
@include('components.btn_procesando')

<div class="row">

  <div class="col-md-4 acciones-div">
    @component('components.specific.select_mes' , ['alloption' => true , 'mes' => $mes ]) @endcomponent
  </div>

  <div class="col-md-8 acciones-div">

    @if( auth()->user()->isAdmin() )
    <a href="{{ route( 'guia.prepara_guias' , '999999' )  }}" class="btn btn-success btn-flat pull-right"> <span class="fa fa-save"></span> Preparar Guias </a>
    @endif

    <a href="#" data-toggle="tooltip" title="Enviar seleccionados" class="btn btn-default btn-flat pull-right enviar-sunat"> <span class="fa fa-envelope"></span> Enviar </a>
    <a href="#" data-toggle="tooltip" title="Seleccionar todos seleccionados" id="select_all" class="btn btn-default btn-flat pull-right"> <span class="fa fa-users"> </span> </a>
  </div>

</div>

<div>
  @include('components.block_elemento')


  @table([ 'id' => 'datatable' , 'url' => route('guia.search_pendientes'), 'thead' => ['N° Oper', 'N° Doc' , 'Refer' , 'Fecha' , 'Clientes' , 'Almacen' ]])
  @endtable
</div>


@endsection
