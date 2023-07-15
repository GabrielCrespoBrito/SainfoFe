@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar Cuenta Bancaria',
  'titulo_pagina'  => 'Modificar Cuenta Bancaria', 
  'bread'  => [  ['Cuentas Bancarias', route('cuenta.index')] , ['Modificar'] ],
])

@slot('contenido')
  @include('cuenta.partials.form')
@endslot  

@endview_data


