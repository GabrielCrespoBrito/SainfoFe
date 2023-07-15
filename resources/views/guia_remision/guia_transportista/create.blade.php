@include('guia_remision.partials.create' , [ 
  'entidad' => 'Cliente',
  'titulo' => 'Guia Transportista', 
  'routeIndex' => route('guia_transportista.index'),
  'routeStore' => route('guia_transportista.store'),
])