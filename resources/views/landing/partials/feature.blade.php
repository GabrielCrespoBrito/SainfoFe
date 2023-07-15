@php
  $classSection = $classSection ?? ''; 
  $classColDesc = $classColDesc ?? ''; 
  $imgRight = $imgRight ?? true; 
@endphp

<section class="section feature-seccion {{ $classSection }} section-md">
  <div class="container">
    <div class="row row-30">
    @if( $imgRight )
      <div class="col-md-6 {{ $classColDesc }}">
        <h2 class="title-icon"><span>{{ $title }}  </span></h2>
          {{ $content }}
      </div>

      <div class="col-md-6 img-col">
        <img src="{{ $img }}" />
      </div>

    @else
      <div class="col-md-6 img-col">
        <img src="{{ $img }}" />
      </div>

      <div class="col-md-6 {{ $classColDesc }}">
        <h2 class="title-icon"><span>{{ $title }}  </span></h2>
          {{ $content }}
      </div>

    @endif
      
    </div>
  </div>
</section>