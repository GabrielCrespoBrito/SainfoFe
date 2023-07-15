@php
$route = $route ?? "";
$target = $target ?? "_blank";
$method = $method ?? "post";
$id = $id ?? "";
$class_name = $class_name ?? "";
@endphp

<form id="{{ $id }}" class="{{ $class_name }}"  target="{{ $target }}" action="{{ $route }}" method="{{ $method }}">
  @CSRF
  <div class="reportes">
    <div class="filtros">
      {{ $content }}
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary btn-flat"> Generar </button>
    </div>
  </div>

</form>