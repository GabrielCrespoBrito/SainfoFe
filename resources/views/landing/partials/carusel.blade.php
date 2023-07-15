@php
  $id = $id ?? '';
  $images = collect($images);
  $time = $time ?? 200000;
@endphp

<div id="{{ $id }}" class="carousel carousel-sainfo slide d-none d-sm-none d-md-block" data-ride="carousel" data-interval="{{ $time }}">

    <div class="carousel-inner">
           
      @foreach( $images->chunk(5) as $images_group )
      <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
        <div class="row">
          @foreach( $images_group as $image )
              <div class="{{ $loop->first ? 'offset-sm-1' : '' }}  col-md-2 div-item">
                <a target="_blank" href="{{ isset($image['href']) ? $image['href'] : '#' }}">
                  <img class="d-block w-100" src="{{ $image['src'] }}" alt="First slide">
                </a>
              </div>        
            @endforeach
          </div>
        </div>
        @endforeach
    </div>

    {{-- <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a> --}}
    <!-- Carousel Control Prev -->

    {{-- <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a> --}}
    <!-- Carousel Control Next -->

</div>
<!-- End of Carousel Example -->