@view_data([
  'layout' => 'layouts.master' , 
  'title'  => "Suscrición",
  'titulo_pagina'  => "Suscrición", 
  'bread'  => [ ['Suscrición']],
  'box_transparent' => true,
])

  @slot('contenido')  
    @component('suscripcion.partials.form', ['suscripcion' => $suscripcion ])
    @endcomponent
  @endslot  

@endview_data