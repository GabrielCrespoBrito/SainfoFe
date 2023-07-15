@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Vehiculos',
  'titulo_pagina'  => 'Vehiculos', 
  'bread'  => [ ['Vehiculos'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('vehiculo.partials.botones')
  @include('vehiculo.partials.table')
  @include('partials.modal_eliminate', ['url' => route('vehiculo.destroy' , 'XX') ])
@endslot  

@endview_data


