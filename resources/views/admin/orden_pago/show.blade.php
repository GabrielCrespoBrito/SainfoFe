@view_data([
  'layout' => 'layouts.master_admin' ,
  'title' => "Orden de pago #{$orden->getIdFormat()}",
  'titulo_pagina' => "Orden de pago #{$orden->getIdFormat()}",
  'bread' => [ ['Ordenes de pago', '#'], ["Orden de Pago #{$orden->getIdFormat()}"] ],
])

@slot('contenido')
  @component('admin.orden_pago.partials.form', ['orden' => $orden, 'isAdmin' => true ])
  @endcomponent  
@endslot

@endview_data