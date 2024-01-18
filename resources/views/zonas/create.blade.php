@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nueva Zona',
  'titulo_pagina'  => 'Nueva Zona', 
  'bread'  => [  ['Zona', route('zonas.index')] , ['Nuevo'] ],
])

@slot('contenido')
  @include('zonas.partials.form')
@endslot  

@endview_data


