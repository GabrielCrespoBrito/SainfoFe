@include('guia_remision.partials.create' , [ 
  'entidad' => 'Proveedor',
  'titulo' => 'Guia de Ingreso', 
  'routeIndex' => route('guia_ingreso.index'),
  'routeStore' => route('guia_ingreso.store'),
])