@extends('layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.css') }}">
@endsection

@section('js')
<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
<script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
<script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
<script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.js') }}"> </script>
<script src="{{ asset('plugins/onscan/onscan.min.js') }}"> </script>
<script type="text/javascript">
  var table = null;
  var url_crear = "{{ route('productos.store') }}";
  var url_edit = "{{ route('productos.update') }}";
  var url_buscar_familias = "{{ route('productos.buscar_grupo') }}";
  var url_consultar_noperacion = "{{ route('productos.consultar_noperacion') }}";
  var url_consultar_codigo = "{{ route('productos.consultar_codigo') }}";
  var url_consultar_datos = "{{ route('productos.consultar_datos') }}";
  var url_eliminar = "{{ route('productos.eliminar') }}";
  var search_parameter = "{{ $search_parameter }}";

  $("#datatable").one("preInit.dt", function() {
    let btn =
      `
    <select class='select-field-producto input-sm form-control'>
      <option ${search_parameter == "0" ? 'selected' : ''} value='codigo'>Codigo</option>
      <option ${search_parameter == "1" ? 'selected' : ''} value='nombre'>Nombre</option>
      <option ${search_parameter == "2" ? 'selected' : ''} value='codigo_barra'>Codigo de Barra</option>
    </select>");    
    `;

    $button = $(btn);
    $("#datatable_filter label").prepend($button);
    $button.button();
  });

  $("body").on('change', '.select-field-producto', function(E) {
    table.draw();
  });

  $(function() {

    table = $('#datatable').DataTable({
      "createdRow": function(row, data, index) {
        $(row).data(data);
      },
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "{{ route('productos.consulta') }}",
        "data": function(d) {
          return $.extend({}, d, {
            "campo_busqueda": $(".select-field-producto").val(),
            "grupo": $("[name=grupo_filter] option:selected").val(),
            "familia": $("[name=familia_filter] option:selected").val(),
          });
        }
      },
      "columns": [{
          data: 'ProCodi',
          searchable: false
        },
        {
          data: 'unpcodi',
          searchable: false
        },
        {
          data: 'ProNomb',
          searchable: false
        },
        {
          data: 'accion',
          searchable: false
        },
      ]
    });
  });
</script>
<script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
<script src="{{ asset('js/productos/index.js') }}"> </script>

@endsection

@section('titulo_pagina', 'Productos')

@section('contenido')

<div class="acciones-div">
  @php
  $empcodi = empcodi();
  $user = auth()->user();
  @endphp
  
  @if( $user->isAdmin() || $empcodi == "029" || $empcodi == "001" )
    <a href="#" class="btn btn-success btn-flat pull-left" data-toggle="modal" data-target="#modalActualizarProducto"> <span class="fa fa-save"></span> Actualizar Inventario </a>
  @endif

  <!--  -->
  <div class="form-group col-md-3">
    <div class="input-group">
      <select name="grupo_filter" required="required" class="form-control">
        <option data-familias="" value=""> -- SELECCIONAR GRUPO -- </option>
        @foreach( $grupos as $grupo )
        <option data-familias="{{ $loop->first ? $grupo->familias() : '' }}" value="{{ $grupo->GruCodi }}">{{ $grupo->GruNomb }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group col-md-3">
    <div class="input-group">
      <select name="familia_filter" required="required" class="form-control">
        <option data-familias="" value=""> -- SELECCIONAR FAMILIA -- </option>
      </select>
    </div>
  </div>
  <!--  -->

  <a href="#" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> Nuevo </a>
</div>

<div class="col-md-12" style="overflow-x: scroll;">
  <table class="table sainfo-table " id="datatable">
    <thead>
      <tr>
        <td> Código </td>
        <td> Unidad </td>
        <td> Descripción </td>
        <td> Acciones </td>
      </tr>
    </thead>
  </table>
</div>

@include('productos.partials.modal_productos')
@include('productos.partials.modal_actualizar_producto')

@endsection