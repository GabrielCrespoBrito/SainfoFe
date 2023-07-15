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
    var url_consulta = "{{ route('familias.search')  }}";
    var url_guardar = "{{ route('familias.guardar') }}";
    var url_editar = "{{ route('familias.editar')  }}";
    var url_eliminar = "{{ route('familias.borrar')  }}";
    var create = {{ (int) $create }};
  </script>
  
  <script src="{{ asset('js/familias/index.js') }}"> </script>
@endsection

@section('titulo_pagina', 'Familias')

@section('contenido')

{{-- loremp-ipsum-odlor --}}

<div class="col-md-12 acciones-div ww">
  <a href="#" id="borrar_grupo" data-toggle="tooltip" title="Eliminar" class="btn btn-danger disabled btn-flat pull-right eliminar-accion"> <span class="fa fa-trash"></span>  </a>
  <a href="#" id="editar_grupo"  data-toggle="tooltip" title="Modificar" class="btn btn-default disabled btn-flat pull-right modificar-accion"> <span class="fa fa-pencil"></span> </a>
  <a href="#" id="nuevo_grupo" data-toggle="tooltip" title="Nueva" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> Nueva</a>
</div>

<!-- table v-t sainfo-table sainfo-noicon sainfo-table ventas-d table_ventas_index -->
<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table class="table sainfo-table" id="datatable" width="100%">
  <thead>
    <tr>
      <td> Código </td>
      <td> Nombre </td>
      <td> Grupo </td>      
    </tr>
  </thead>
  </table>
</div>

@include('familias.partials.modal_familia')


@endsection

