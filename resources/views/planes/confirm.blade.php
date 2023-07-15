@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Planes',
  'titulo_pagina'  => 'Planes', 
  'bread'  => [ ['Planes'] ],
  // 'assets' => ['js' => ['helpers.js']]
  'assets' => ['js' => ['helpers.js','planes/confirm.js']]
])

  @slot('contenido')
    @component('planes.partials.confirm', ['plan_duracion' => $plan_duracion ])
    @endcomponent

    @include('planes.modal_terminos', ['condiciones' => $condiciones ])

  @endslot  

@endview_data