@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nueva Cuenta Bancaria',
  'titulo_pagina'  => 'Nueva Cuenta Bancaria', 
  'bread'  => [  ['Cuentas Bancarias', route('cuenta.index')] , ['Nueva'] ],
])

@slot('contenido')
  @include('cuenta.partials.form')
@endslot  

@endview_data


