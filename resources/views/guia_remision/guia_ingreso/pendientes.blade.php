
@extends('layouts.master')

@section('bread')
<li> <a href="{{ route('guia.index') }}"> Guia remisión </a> </li>
<li> Pendientes </li>
@endsection

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/popover/style.css') }}"/>
@endsection

@section('js')
  <script src="{{ asset('plugins/popover/script.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"></script>
	<script type="text/javascript">
	var url_search	= "{{ route('guia.search_pendientes') }}";
  var url_sendcorre  = "{{ route('guia.sent_email') }}";

	</script>
	<script type="text/javascript" src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
	<script type="text/javascript" src="{{ asset('js/guia/index.js') }}"></script>
	</script>
@endsection

@section('titulo_pagina', 'Guia remisión Pendientes')

@section('contenido')


<div class="row">

   <div class="col-md-4 acciones-div">
    @component('components.specific.select_mes' , ['alloption' => true ]) @endcomponent
  </div>

</div>


 @table([ 'id' => 'datatable' ,  'url' =>  "{{ route('guia.search_pendientes') }}" , 'thead' => ['N° Oper', 'N° Doc' , 'Refer' , 'Fecha' , 'Clientes' , 'Almacen' , 'Estado' , '' ]])
  @endtable

  @include('cotizaciones.partials.modal_redactar_correo', ['asunto' => 'Guia de remisión'])

@endsection