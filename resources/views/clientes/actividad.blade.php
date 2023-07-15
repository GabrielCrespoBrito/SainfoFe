@extends('layouts.master')

@section('titulo_pagina' , 'Actividad de Clientes' )

@section('js')

  <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script>
  <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
  <script type="text/javascript">
    var url_route_actividad = "{{ route('clientes.actividad_search') }}";
  </script>
  <script src="{{ asset('js/clientes/actividad.js') }}"></script>
@endsection

@section('css')
  <!-- Style -->
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}"/>   
@endsection

@section('titulo_pagina', 'Ventas')

@section('contenido')

<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table class="table v-t sainfo-table sainfo-noicon sainfo-table" id="datatable">
  <thead>
    <tr>
      <td> Cliente </td>    
      <td> Fecha </td>
      <td> Acción </td>      
      <td> Descripcion </td>            
      <td> Documento/s </td>            
    </tr>
  </thead>
  </table>
</div>

@endsection


