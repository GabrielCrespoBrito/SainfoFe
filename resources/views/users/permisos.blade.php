@view_data([
  'layout' => 'layouts.master' , 
  'title'  => "Permisos del usuario {$user->nombre()}",
  'titulo_pagina'  => "Permisos del usuario {$user->nombre()}", 
  'bread'  => [ ['Usuarios' , route('usuarios.index') ], [ $user->nombre() ] , ['Permisos'] ],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'usuarios/index.js', 'users/permisos.js'] ]
])

@slot('contenido')
  @include('users.partials.form_permisos')
@endslot  

@endview_data