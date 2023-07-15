@view_data([
'layout' => 'layouts.master_admin',
'title' => "Permisos de {$user->usulogi}",
'titulo_pagina' => "Permisos de {$user->usulogi}",
'bread' => [ ['Usuarios' , route('admin.usuarios.index') ], [ $user->nombre() ] , ['Permisos'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'usuarios/index.js', 'users/permisos.js'] ]
])

@slot('contenido')
  @include('admin.usuarios-permisos.partials.form')
@endslot

@endview_data