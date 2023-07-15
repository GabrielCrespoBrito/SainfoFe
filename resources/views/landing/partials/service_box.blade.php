@php  
  $attrDelay = '';
  if(isset($delay)){
    $delay = $delay ?? 
    $attrDelay = "data-wow-delay='{$delay}'";
  }
  $icon = $icon ?? 'mercury-icon-globe';

@endphp

<div class="col-md-4 box-icon-1 wow fadeInUp" {!! $attrDelay !!}>
    <div class="icon novi-icon {{ $icon }}"></div>
    <h5 class="title">{{ $title  }}</h5>
    <p class="text">{{ $descripcion }}</p>
</div>