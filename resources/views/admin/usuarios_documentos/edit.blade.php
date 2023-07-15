@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Modificar Usuario-Documentos',
'titulo_pagina' => 'Modificar Usuario-Documentos',
'bread' => [ [ 'Usuario-Documento', route('admin.usuarios_documentos.mantenimiento')] , ['Modificar'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'print_js/ConectorPlugin.js', 'ventas/print_test.js', '', 'usuarios_documentos/create.js' ]]
])

@slot('contenido')


<form  id="usuario_documento"  action="{{ route('admin.usuarios_documentos.update', ['id' => $usuario_documento->ID ]) }}" method="post">
  @csrf
  @method('PUT')
  <input type="hidden" name="method_field" value="PUT">

  @include('admin.usuarios_documentos.partials.usuario')
  @include('admin.usuarios_documentos.partials.serie')

  <hr>
  @include('admin.usuarios_documentos.partials.formato')

  <hr>
  @include('admin.usuarios_documentos.partials.impresion')

  <hr>
  @include('admin.usuarios_documentos.partials.opciones')

  @include('admin.usuarios_documentos.partials.save')

</form>


@endslot

@endview_data