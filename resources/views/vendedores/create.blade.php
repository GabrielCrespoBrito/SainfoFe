@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nuevo Vendedor',
  'titulo_pagina'  => 'Nuevo Vendedor', 
  'bread'  => [  ['Vendedores', route('vendedor.index')] , ['Nuevo'] ],
])

@slot('contenido')
  @include('vendedores.partials.form')
@endslot  

@endview_data


