@php
  $id = $id ?? '';
  $testimonios = collect($testimonios);
  $time = $time ?? 200000;
@endphp
<div id="{{ $id }}" class="carousel carusel-testimonios carousel-sainfo slide d-md-block" data-ride="carousel" data-interval="{{ $time }}">
    <div class="carousel-inner">
      @foreach( $testimonios->chunk(5) as $testimonios_group )
      <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
        <div class="row">
          @foreach( $testimonios_group as $testimonio )
              <div class="col-md-4 div-item">
                <div class="info-container">
                <div class="info-testimonio">
                  <div class="info-slot-testimonio">
                  
                  @if($testimonio['testimonio_text'])
                    <div class="testimonio_text">{{ $testimonio['testimonio_text'] }}</div>
                  @endif

                  @if($testimonio['url_video'])
                    <div class="testimonio_video" > <a data-lightgallery="item" href="{{ $testimonio['url_video'] }}"> <span class="fa fa-youtube-play"></span> </a> </div>
                  @endif
                  </div>
                    <div class="info-slot-razon_social">
                      <span class="razon_social">{{ $testimonio['cliente']['razon_social'] }}</span>
                      <span class="ruc">{{ $testimonio['cliente']['ruc'] }}</span>
                    </div>
                    <div class="info-slot-representante">
                      <span class="representante">{{ $testimonio['representante'] }}</span>
                      <span class="cargo">{{ $testimonio['cargo'] }}</span>
                    </div>
                  </div>
                <img class="d-block w-100" src="{{ $testimonio['path'] }}" alt="First slide">                
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endforeach
    </div>
</div>