@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Usuarios-Documentos',
'titulo_pagina' => 'Usuarios-Documentos',
'bread' => [ [ 'Usuario-Documento', route('admin.usuarios_documentos.mantenimiento')] , ['Nuevo'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'print_js/ConectorPlugin.js', 'ventas/print_test.js', '', 'usuarios_documentos/create.js' ]]
])

@slot('contenido')

<form action="{{ route('admin.usuarios_documentos.store') }}" id="usuario_documento" method="post">
  @csrf

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