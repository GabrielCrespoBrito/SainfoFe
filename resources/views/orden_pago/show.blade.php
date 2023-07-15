@view_data([
  'layout' => 'layouts.master' , 
  'title'  => "Orden de pago #{$orden->getIdFormat()}",
  'titulo_pagina'  => "Orden de pago #{$orden->getIdFormat()}", 
  'bread'  => [ ['Ordenes de pago', route('suscripcion.ordenes.index')], ["Orden de Pago #{$orden->getIdFormat()}"] ],
])
  @slot('contenido')  
    @component('orden_pago.partials.form', ['orden' => $orden, 'isAdmin' => false ])
    @endcomponent
  @endslot  

@endview_data