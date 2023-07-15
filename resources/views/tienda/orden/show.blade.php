@view_data([
  'layout' => 'layouts.master' , 
  'title'  => $data['orden_nombre'],
  'titulo_pagina'  => $data['orden_nombre'], 
  'bread'  => [ ['Tienda'] , [ 'Ordenes', route('tienda.orden.index')  ] , [$data['orden_nombre']] ],
])

@slot('contenido')
  @include('tienda.orden.partials.form.form')
@endslot

@endview_data
