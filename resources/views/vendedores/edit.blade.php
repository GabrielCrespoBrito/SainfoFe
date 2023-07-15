@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar Vendedor',
  'titulo_pagina'  => 'Modificar Vendedor', 
  'bread'  => [  ['Vendedores', route('vendedor.index')] , ['Modificar'] ],
])

@slot('contenido')
  @include('vendedores.partials.form')
@endslot  

@endview_data


