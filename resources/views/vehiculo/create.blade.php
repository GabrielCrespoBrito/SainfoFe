@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nuevo vehiculo',
  'titulo_pagina'  => 'Nuevo vehiculo', 
  'bread'  => [ ['Vehiculos' , route('vehiculo.index') ] , ['Crear'] ],
])

@slot('contenido')
  @include('vehiculo.partials.form')
@endslot  

@endview_data


