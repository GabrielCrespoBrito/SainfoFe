@extends('layouts.master')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
@endsection

@section('js')
  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script type="text/javascript">
    var active_or_disable_;
    var active_ordisable_tr;
    var table;
    var accion = "create";    
    var url_consulta  = "{{ route('marcas.search')  }}";
    var url_guardar   = "{{ route('marcas.guardar') }}";
    var url_editar    = "{{ route('marcas.editar')  }}";
    var url_eliminar    = "{{ route('marcas.borrar')  }}";
    var last_id = "{{ $last_id }}";
    var create = {{ (int) $create }};
    // QueEsLoMejorQuePuedoCenar?
  </script>
  <script src="{{ asset('js/marcas/index.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Marcas')

@section('contenido')

<div class="col-md-12 acciones-div ww">
  <a href="#" id="borrar_grupo" data-toggle="tooltip" title="Eliminar" class="btn btn-danger disabled btn-flat pull-right eliminar-accion"> <span class="fa fa-trash"></span>  </a>
  <a href="#" id="editar_grupo"  data-toggle="tooltip" title="Modificar" class="btn btn-default disabled btn-flat pull-right modificar-accion"> <span class="fa fa-pencil"></span> </a>
  <a href="#" id="nuevo_grupo" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span>  Nueva</a>
</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table class="table sainfo-table" id="datatable">
  <thead>
    <tr>
      <td> Código </td>
      <td> Nombre </td>
      <td>  </td>
    </tr>
  </thead>
  </table>
</div>

@include('marcas.partials.modal_producto')
@endsection