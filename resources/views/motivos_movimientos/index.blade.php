@extends('layouts.master')

@section('js')
  <script type="text/javascript">
    var accion = "create";
    var url_crear   = "{{ route('cajas.motivos_save', $type ) }}";
    var url_modificar    = "{{ route('cajas.motivos_edit' , $type)  }}";
    var url_eliminar    = "{{ route('cajas.motivos_delete' , $type)  }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>  
  <script src="{{ asset('js/motivos/index.js') }}"> </script>
@endsection

<?php $nombre = "Motivos de $type"; ?>

@section('titulo_pagina', $nombre  )

@section('contenido')

<div class="col-md-12 acciones-div ww">

  <a href="#" id="eliminar" class="btn btn-danger btn-flat pull-right"> <span class="fa fa-trash"></span>  </a>

  <a href="#" id="modificar"  data-toggle="tooltip" title="Modificar" class="btn btn-default btn-flat pull-right modificar-accion"> <span class="fa fa-pencil"></span> </a>

  <a href="#" id="nuevo" data-toggle="tooltip" title="Nueva" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> </a>

</div>


@component('components.table' , ['id' => 'table_motivos' , 
  'thead' => ['Codigo', 'Nombre']
])
  @slot('body')
      @foreach( $motivos as $motivo )
        <tr>
          <td>{{ $motivo->codigo() }}</td>
          <td>{{ $motivo->nombre() }}</td>        
        </tr>
      @endforeach
  @endslot

@endcomponent

@include('motivos_movimientos.partials.modal')

@endsection