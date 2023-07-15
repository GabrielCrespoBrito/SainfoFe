@php

$class_name = $class_name ?? "";
$descripcion_class = $descripcion_class ?? '';
$descripcion = $descripcion ?? 'SIN OTRO EN PARTICULAR Y ESPERANDO VERNOS FAVORECIDOS CON SUS GRATAS ORDENES, NOS SUSCRIBIMOS DE USTEDES.';
$receptor = $receptor ?? 'DPTO. VENTAS';
$receptor_class = $receptor_class ?? '';
$receptor_text_class = $receptor_text_class ?? '';

@endphp

<div class="despedida-coti {{ $class_name }}">
  <div class="{{ $descripcion_class }}"> {{ $descripcion }} </div>
  <div class="{{ $receptor_class }}"> <span class="{{ $receptor_text_class }}"> {{ $receptor }} </span> </div>
</div>
