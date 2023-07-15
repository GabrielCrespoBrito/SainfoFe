@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar vehiculo',
  'titulo_pagina'  => 'Modificar vehiculo', 
  'bread'  => [ ['Vehiculos' , route('vehiculo.index') ] , ['Modificar'] ],
])

@slot('contenido')
  @include('vehiculo.partials.form')
@endslot  

@endview_data


