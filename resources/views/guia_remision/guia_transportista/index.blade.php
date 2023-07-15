@php
  $format = $format ?? 0;
@endphp

@include('guia_remision.partials.index' , [ 
  'entidad' => 'Cliente', 
  'titulo' => 'Guia de Transportista', 
  'routeSearch' => route('guia_transportista.search'),
  'routeCreate' => route('guia_transportista.create'),
  'isSalida' => false,
  'format' => $format
])