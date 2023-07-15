@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Listas de Precios',
  'titulo_pagina'  => 'Listas de Precios', 
  'bread'  => [ ['Listas de precios',  route('listaprecio.index')] , ['Nueva'] ],
])

@slot('contenido')
  @include('listaprecio.partials.form' , ['listaprecio' => $listaprecio, 'accion' => 'create'])
@endslot

@endview_data