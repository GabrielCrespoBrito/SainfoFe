@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Usuarios',
'titulo_pagina' => 'Usuarios',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','usuarios/index.js' ]]
])

@slot('contenido')

<form id="eliminar-user" action="#" style="display: none">
  @csrf
  <input name="codigo">
</form>

<div class="acciones-div">
  <a data-url="{{ route('admin.usuarios.form', ['id' => 'XXX']) }}"  href="{{ route('admin.usuarios.form') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">

<table data-url="{{ route('admin.usuarios.search') }}" class="table table-bordered table-hover sainfo-table user-table" id="datatable">
  <thead>
    <tr>
      <td> Código </td>
      <td> Nombre </td>
      <td> Empresa </td>
      <td> Roles </td>
      <td> Estado </td>
      <td> Acciones </td>
    </tr>
  </thead>
</table>
</div>

@include('admin.usuarios.partials.modal_usuario', ['roles' => $roles])

@include('admin.usuarios.partials.modal_eliminar_usuario')

@endslot

@endview_data