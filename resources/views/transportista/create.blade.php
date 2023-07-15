@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nuevo Transportista',
  'titulo_pagina'  => 'Nuevo Transportista', 
  'bread'  => [ ['Transportistas' , route('transportista.index') ], ['Crear'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('transportista.partials.form')
@endslot  

@endview_data


