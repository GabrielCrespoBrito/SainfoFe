@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Usuarios-Documentos',
'titulo_pagina' => 'Usuarios-Documentos',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','usuarios_documentos/index.js', 'admin/mix/documentos_mix.js' ]]
])

@section('js')
	<script type="text/javascript">
	var url_consulta = "{{ route('admin.usuarios_documentos.search') }}";
	var url_consulta_users = "{{ route('admin.usuario-empresa.search_users') }}";
  
	</script>
@endsection

@slot('contenido')

{{-- loremp-ipsum-odlor-loremp-ipsum-odlor-loremp-ipsum-odlor --}}

@component('admin.partials.filtros_empresa', ['empresas' => $empresas])

@slot('content')
    <div class="col-md-5">
    <select
      id="user_id"
      data-reloadtable="table"
      name="user_id"
      class="form-control input-sm text-uppercase">
    </select>
  </div>
@endslot

@endcomponent

	<div class="acciones-div">
		<a data-route="{{ route('admin.usuarios_documentos.create', [ 'id_user' => 'all', 'id_empresa' => '@@@']) }}" href="{{ route('admin.usuarios_documentos.create', [ 'id_user' => 'all', 'id_empresa' => 'all']) }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>

	</div>


<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
	<table class="table table-bordered table-hover sainfo-table user-table" id="datatable" >
	<thead>
		<tr>
      <td> ID </td>
      <td> Usuario </td>
      <td> Local </td>
      <td> Tipo Doc. </td>
      <td> Serie </td>
      <td> Numero </td>
      <td> Activo </td>
      <td> Acciones </td>
		</tr>
	</thead>
	</table>
</div>

@endslot

@endview_data