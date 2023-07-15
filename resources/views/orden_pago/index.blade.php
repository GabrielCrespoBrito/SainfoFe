@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Ordenes de pago',
  'titulo_pagina'  => 'Ordenes de pago', 
  'bread'  => [ ['Ordenes de pago'] ],
  // 'assets' => ['js' => ['helpers.js']]
  'assets' => ['js' => ['helpers.js','planes/index.js']]
])

  @slot('contenido')  
    @component('orden_pago.partials.list', ['ordenes' => $ordenes, 'isAdmin' => false ])
    @endcomponent
  @endslot  

@endview_data