@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Modificar Testimonio',
'titulo_pagina' => 'Modificar Testimonio',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  @include('admin.pagina.cont-testi.partials.form')

@endslot
@endview_data
