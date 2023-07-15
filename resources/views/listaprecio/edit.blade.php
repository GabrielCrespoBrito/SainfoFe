@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar Listas de Precios',
  'titulo_pagina'  => 'Modificar Listas de Precios', 
  'bread'  => [ ['Listas de precios',  route('listaprecio.index')] , ['Modificar'] ],
])

@slot('contenido')
  @include('listaprecio.partials.form' , ['listaprecio' => $listaprecio, 'accion' => 'edit'])
@endslot  

@endview_data

