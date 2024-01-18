@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar Zona',
  'titulo_pagina'  => 'Modificar Zona', 
  'bread'  => [  ['Zonas', route('zonas.index')] , ['Modificar'] ],
])

@slot('contenido')
  @include('zonas.partials.form')
@endslot  

@endview_data


