@php

$class_name = $class_name ?? "";
$descripcion_class = $descripcion_class ?? '';
$descripcion = $descripcion ?? 'SIN OTRO EN PARTICULAR Y ESPERANDO VERNOS FAVORECIDOS CON SUS GRATAS ORDENES, NOS SUSCRIBIMOS DE USTEDES.';
$receptor = $receptor ?? 'DPTO. VENTAS';
$receptor_class = $receptor_class ?? '';
$receptor_text_class = $receptor_text_class ?? '';
$show_peso = $show_peso ?? false;
$usuario_nombre = $usuario_nombre ?? '';
@endphp

<div class="despedida-coti {{ $class_name }}">
  <div class="{{ $descripcion_class }}"> {{ $descripcion }} </div>
    <div class="{{ $receptor_class }}">
      <div class="{{ $receptor_text_class }}"> {{ $receptor }} </div> 
      <div class="text-center"> {{ $usuario_nombre }}  
      @if($show_peso)
      <span class="position-absolute" style="right: 0"> Total Kilos Netos <span class="bold">{{ decimal( $peso, 2 ) }}</span></span> 
      @endif
      </div>
  </div>
</div>