@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Administracion - Ordenes de pago',
  'titulo_pagina'  => 'Administracion - Ordenes de pago', 
  'bread'  => [ ['Administracion - Ordenes de pago'] ],
  // 'assets' => ['js' => ['helpers.js']]
  'assets' => ['js' => ['helpers.js','planes/index.js']]
])

  @slot('contenido')  
    @component('orden_pago.partials.list', ['ordenes' => $ordenes, 'isAdmin' => true ])
    @endcomponent
  @endslot  

@endview_data