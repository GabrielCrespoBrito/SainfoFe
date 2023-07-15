@php
  $withPagination = true;
  $time = $time ?? 3000;
@endphp
 
<!--Swiper-->
<section class="section section-slider slider-sainfo swiper-container swiper-slider swiper-slider-1 context-dark" data-loop="false" data-autoplay="{{ $time }}" data-simulate-touch="false">
  <div class="swiper-wrapper">
    {{ $content }}
  </div>

@if($withPagination)
  <!--Swiper Pagination-->
  <div class="swiper-pagination-wrap">
    <div class="container">
      <div class="row">
        <div class="col-md-9 col-lg-7 offset-md-1 offset-xxl-0">
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </div>
@endif
</section>