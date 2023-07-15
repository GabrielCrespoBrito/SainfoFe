@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Modificar Banner',
'titulo_pagina' => 'Modificar Testimonio',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  @include('admin.pagina.banners.partials.form')

@endslot
@endview_data
