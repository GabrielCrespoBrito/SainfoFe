@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar empresa de transporte',
  'titulo_pagina'  => 'Modificar empresa de transporte', 
  'bread'  => [ ['Empresas de transporte' , route('empresa_transporte.index') ] , ['Modificar'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('transportista.partials.form')
@endslot  

@endview_data


