@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Planes',
  'titulo_pagina'  => 'Planes', 
  'bread'  => [ ['Planes'] ],
  'assets' => ['js' => ['helpers.js','planes/index.js']]
])

@slot('contenido')
  @component('planes.partials.list', [
    'planes' => $planes,
    'plan_current' => $plan_current,
    'nombrePlan' => $nombrePlan 
  ])
  @endcomponent
@endslot

@endview_data