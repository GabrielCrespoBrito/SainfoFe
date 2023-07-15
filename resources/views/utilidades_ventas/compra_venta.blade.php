@extends('layouts.master')
<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
  <link href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}">
  <link href="https://cdn.datatables.net/autofill/2.3.1/css/autoFill.dataTables.min.css">    
@endsection

@section('js')
  <script src="{{asset('plugins/datatable/jquery.dataTables.min.js') }}"> </script> 
  <script src="{{asset('plugins/datatable/dataTables.bootstrap.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"> </script>
  <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="https://cdn.datatables.net/autofill/2.3.1/js/dataTables.autoFill.min.js"></script> 
  <script type="text/javascript">
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_buscar_producto = "{{ route('reportes.buscar_producto') }}";
    var url_buscar_producto_datos = "{{ route('productos.consultar_alldatos') }}";
    var url_route_productos_consulta = "{{ route('productos.consulta') }}";
  </script>
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script src="{{ asset('js/reportes/compra_venta.js') }}"></script>
@endsection

@section('titulo_pagina', 'Compra y venta de articulos')
@section('contenido')

<?php 
  $class_adicional = "reportes";
?>

@include('reportes.partials.botones')
@include('reportes.partials.filtros')


<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">
  <table style="width: 100%;" class="table sainfo-table" id="datatable">
  <thead>
    <tr>
      <td> Fecha </td>    
      <td> T.Doc </td>
      <td> N° Doc </td>    
      <td> Razón social </td>
      <td> Unidad </td>        
      <td> Cantidad </td>    
      <td> Moneda </td>
      <td> Precio </td>
    </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

@include('reportes.partials.modal_productos')

@endsection

