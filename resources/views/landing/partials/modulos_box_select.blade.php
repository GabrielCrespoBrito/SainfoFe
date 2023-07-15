@php
    $active = $active ?? false;
@endphp

<div class="col-md-3 box-modules wow fadeInUp">
  <div class="box-icon-2 {{ $active ? 'active' : '' }}" data-target="#{{ $id }}">
    <h5 class="title text-light" style="text-align: center"> {{ $descripcion }} </h5>
  </div>
</div>




