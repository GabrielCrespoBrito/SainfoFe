@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Asociar Empresa al Usuario',
'titulo_pagina' => 'Asociar Empresa al Usuario',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')
  @include('admin.usuarios-local.partials.form')
@endslot

@endview_data