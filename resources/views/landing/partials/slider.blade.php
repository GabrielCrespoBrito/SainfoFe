{{--| Swiper |--}}
{{--| Swiper |--}}
<section class="section section-slider slider-sainfo swiper-container swiper-slider swiper-slider-1 context-dark" data-loop="true" data-autoplay="5000" data-simulate-touch="false">

  <div class="swiper-wrapper">

  @foreach( $banners as $banner )
    <div class="swiper-slide punto-venta" data-slide-bg="{{ str_replace( '\\', '/', $banner['path'] ) }}">
    </div>
  @endforeach
  
  </div>

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


</section>