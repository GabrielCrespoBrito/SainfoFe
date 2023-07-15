@php
  $format = $format ?? 0;
@endphp

@include('guia_remision.partials.index' , [ 
  'entidad' => 'Proveedor', 
  'titulo' => 'Guia de Ingreso', 
  'routeSearch' => route('guia_ingreso.search'),
  'routeCreate' => route('guia_ingreso.create'),
  'isSalida' => false,
  'format' => $format
])