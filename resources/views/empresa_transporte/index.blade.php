@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Empresas de transporte',
  'titulo_pagina'  => 'Empresas de transporte', 
  'bread'  => [ ['Empresas de transporte'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('empresa_transporte.partials.botones')
  @include('empresa_transporte.partials.table')
  @include('partials.modal_eliminate', ['url' => route('empresa_transporte.destroy' , 'XX') ])
@endslot  

@endview_data


