@view_data([
  'layout' => 'layouts.master' , 
  'title'  => "Orden de pago #{$orden->getIdFormat()}",
  'titulo_pagina'  => "Orden de pago #{$orden->getIdFormat()}", 
  'bread'  => [ ['Ordenes de pago', route('suscripcion.ordenes.index.admin')], ["Orden de Pago #{$orden->getIdFormat()}"] ],
  // 'assets' => ['js' => ['helpers.js']]
  // 'assets' => ['js' => ['helpers.js','planes/index.js']]
])

  @slot('contenido')  
    @component('orden_pago.partials.form', ['orden' => $orden, 'isAdmin' => true ])
    @endcomponent
  @endslot  

@endview_data