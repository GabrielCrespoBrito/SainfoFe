@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Nuevo Testimonio',
'titulo_pagina' => 'Nuevo Testimonio',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  @include('admin.pagina.cont-testi.partials.form')

@endslot
@endview_data
