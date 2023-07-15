@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Exportar ',
  'titulo_pagina' => 'Exportar documentos',
  'bread'  => [ ['Exportar documentos'] ],
  // 'assets' => ['js' => ['helpers.js', '../plugins/download/download.js', 'export/script.js']]
  // 'assets' => ['js' => ['helpers.js']]

])
@slot('contenido')

  @include('export.partials.form')

@endslot  

@endview_data
