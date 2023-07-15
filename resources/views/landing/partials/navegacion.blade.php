@php
  $links = $links ?? [];
@endphp

<!-- Breadcrumbs-->
<section class="breadcrumbs-custom bg-image novi-background bg-primary">
  <div class="container">
    <ul class="breadcrumbs-custom-path">
      <li><a href="{{ route('landing.index') }}">Inicio</a></li>
      @foreach( $links as $link )
        <li class="{{ $loop->last ? 'active' : ''}}"> 
          @isset( $link['src'] )
            <a href="{{ $link['src'] }}"> {{ $link['text'] }} </a>          
          @else 
              {{ $link['text'] }} 
          @endisset          
        </li>
      @endforeach
    </ul>
  </div>
</section>