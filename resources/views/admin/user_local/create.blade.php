@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Nuevo Usuario Local',
'titulo_pagina' => 'Nuevo Usuario Local',
'bread' => [ ['Inicio'] ],
])

@slot('contenido')

  @include('admin.user_local.partials.form', ['action' => 'create' ])
  
@endslot

@endview_data