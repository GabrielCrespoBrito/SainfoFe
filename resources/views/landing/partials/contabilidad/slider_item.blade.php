@php
  $content = $content ?? null; 
@endphp

<div class="swiper-slide facturacion" data-slide-bg="{{ $img  }}">
  <div class="swiper-slide-caption section-lg">
    <div class="container">
      <div class="row">
        <div class="col-md-9 col-lg-10 offset-md-1 offset-xxl-0">
          {{ $content }}
        </div>
      </div>
    </div>
  </div>
</div>