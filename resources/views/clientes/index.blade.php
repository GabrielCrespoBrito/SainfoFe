@extends('layouts.master')

<script type="text/javascript">
  var url_edit_cliente = "{{ route('clientes.edit') }}";  
  var url_consulta_departamento = "{{ route('consulta.departamento') }}";
  var url_crear_cliente = "{{ route('clientes.create') }}";
  var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
  var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";
  var url_eliminar_cliente = "{{ route('clientes.eliminar') }}";
  var url_restaurar = "{{ route('clientes.restaurar') }}";
  var url_consultar_cliente = "{{ route('clientes.consultar_datos') }}";
  var accion_default = "{{ $accion }}";
  var ruc_crear = "{{ $ruc }}"
  var table; 
</script>

@add_assets(['libs' => [ 'datatable' , 'select2'] , 'js' => ['helpers.js' , 'clientes/scripts.js']  ]) 
@endadd_assets

@push('js')


	<script type="text/javascript">

  var url_edit_cliente = "{{ route('clientes.edit') }}";  
  var url_consulta_departamento = "{{ route('consulta.departamento') }}";
	var url_crear_cliente = "{{ route('clientes.create') }}";
	var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
  var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";
  var url_eliminar_cliente = "{{ route('clientes.eliminar') }}";
  var url_consultar_cliente = "{{ route('clientes.consultar_datos') }}";
  var accion_default = "{{ $accion }}";
  var ruc_crear = "{{ $ruc }}"
  var table; 

  $(function () {

    table = $('#datatable').DataTable({
      "processing" : true,
    	"serverSide" : true,
      "ajax": {
        url : "{{ route('clientes.consulta') }}",
        data : function(d) {
         return $.extend( {} , d , {
            'tipoentidad_id' : $("[name=tipoentidad_id] option:selected").val(),
            'deleted': $("[name=deleted]:checked").val(), 
          })
        }
      },
    	"columns" : [
    		{ data : 'PCCodi'  } ,
    		{ data : 'TipCodi' } ,
    		{ data : 'PCRucc'  } ,
        { data : 'PCNomb' } ,
    		{ data : 'PCDire'  , render : function(value){

          if(value){
            let short = value.length > 70 ? value.substr(0,70).concat('...') : value;
            return `<span title="${value}">${ short }</span>`
          }

          return '';

        } } ,
        { data : 'acciones' ,
          searchable:false, 
          sortable:false
        }
      ]
    })

  $("[name=tipoentidad_id]").on('change', () => table.draw() );

  });

	</script>
@endpush

@section('titulo_pagina', 'Clientes')

@section('contenido')

<form id="eliminar-user" action="#" style="display: none">
	@csrf	
	<input name="codigo">
</form>

<div class="acciones-div">

  <div class="row">

    <div class="form-group  col-md-3">
      {!! Form::select('tipoentidad_id' , cacheHelper('tipocliente.all')->pluck( 'TippNomb', 'TippCodi') , null, ['class' => 'form-control'] )  !!} 
    </div> 

  <div class="form-group col-md-3">
    <div style="margin-top:5px;">
      <label> <input type="checkbox" name="deleted" value="1"> Mostrar eliminados </label>
    </div>
  </div>


    
    <div class="col-md-6">
      <a href="#" class="btn btn-primary btn-flat pull-right crear-nuevo-cliente"> <span class="fa fa-plus"></span> Nuevo </a>
    </div>

  </div>

</div>

{{-- <div class="col-md-12" style="overflow-x: scroll;"> --}}
<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table class="table sainfo-table sainfo-noicon" id="datatable" style="font-size: .9em">
  <thead>
  	<tr>
  		<td> Código </td>
  		<td> Tipo </td>
      <td> Ruc </td>    
  		<td> Razón social </td>		
  		<td> Direcciòn </td>
      <td> Acciones </td>   
  	</tr>
  </thead>
  </table>
</div>


@include('clientes.partials.modal_clientes_proveedores')
@include('clientes.partials.modal_eliminar_clientes_proveedores')

@endsection