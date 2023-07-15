@php
  $format = $format ?? 0;
  $mes = $mes ?? date('Ym');
  $status = $status ?? null;
@endphp

@include('guia_remision.partials.index' , [ 
  'entidad' => 'Cliente', 
  'format' => $format,
  'mes' => $mes,
  'status' => $status,
  'motivo_traslado' => $motivo_traslado ?? null,
  'titulo' => 'Guia de Remisión', 
  'routeSearch' => route('guia.search'),
  'routeCreate' => route('guia.create'),
  'isSalida' => true,
])