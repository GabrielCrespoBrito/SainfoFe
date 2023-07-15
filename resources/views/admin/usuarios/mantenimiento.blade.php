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
  <a href="#" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
</div>

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

@include('admin.usuarios.partials.modal_usuario', ['roles' => $roles])
@include('admin.usuarios.partials.modal_eliminar_usuario')

@endslot

@endview_data