@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Nuevo Banner',
'titulo_pagina' => 'Nuevo Banner',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  @include('admin.pagina.banners.partials.form')

@endslot
@endview_data
