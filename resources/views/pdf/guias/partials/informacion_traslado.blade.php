@php
$class_name = $class_name ?? "";
$class_table = $class_table ?? '';
$border_color = $border_color ?? null;
@endphp

<div class="informacion_traslado  {{ $class_name }}">
  <table class="{{ $class_table }}" width="100%">

    <tr>
      @include('pdf.guias.partials.motivo_traslado',     [ 'border_color' => $border_color ])
      @if(!$guia2->isIngreso())
      @include('pdf.guias.partials.unidad_transporte' ,  [ 'border_color' => $border_color ] )
      @include('pdf.guias.partials.empresa_transporte' , [ 'border_color' => $border_color ] )
      @endif
    </tr>

  </table>
</div>