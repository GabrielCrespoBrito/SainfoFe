@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Modificar Usuario Local',
'titulo_pagina' => 'Modificar Usuario Local',
'bread' => [ ['Inicio'] ],
])

@slot('contenido')

    @include('admin.user_local.partials.form', ['action' => 'edit'])



@endslot

@endview_data

