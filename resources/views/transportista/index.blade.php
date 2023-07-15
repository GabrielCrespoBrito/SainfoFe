@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Transportistas',
  'titulo_pagina'  => 'Transportistas', 
  'bread'  => [ ['Transportistas'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('transportista.partials.botones')
  @include('transportista.partials.table')
  @include('partials.modal_eliminate', ['url' => route('transportista.destroy' , 'XX') ])
@endslot  

@endview_data