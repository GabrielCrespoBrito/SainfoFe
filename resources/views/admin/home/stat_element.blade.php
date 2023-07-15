@php
  $descripcion = $descripcion ?? 'pencil';
  $valor = $valor ?? '-';
  $link = $link ?? '#';
  $class_name = $class_name ?? 'col-lg-3 col-xs-6';
@endphp

<div class="{{ $class_name }}">
  <div class="small-box">
    <div class="inner">
      <h3>{{ $valor }}</h3>
      <p> {{ $descripcion }} </p>
    </div>
    <div class="icon">
      <i class="fa"></i>
    </div>
    <a href="{{ $link }}" class="small-box-footer">Mas Información <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>