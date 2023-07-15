@php
$ruc = $ruc ?? '';
$class_name = $class_name ?? '';
$class_ruc = $class_ruc ?? '';
$class_nombre = $class_nombre ?? '';
$class_numeracion = $class_numeracion ?? '';
@endphp

<div class="id-documento {{ $class_name }}">
  @if( $ruc )
  <div class="ruc {{ $class_ruc }}"> <span class="text">R.U.C.</span> {{ $ruc }} </div>
  @endif
  <div class="nombre {{ $class_nombre }}"> {{ $nombre_documento }} </div>
  <div class="numeracion {{ $class_numeracion }}"> {{ $documento_id }} </div>
</div>