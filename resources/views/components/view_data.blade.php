{{-- extends --}}
@extends($layout)

{{-- title de la pagina --}}
@section( 'title', $title )

@if( isset($js_before) )
@push('js')
  {!!  $js_before !!}
@endpush
@endif


{{-- agregar assets --}}
@if(isset($assets))
@php
  $assets_add = [];
  if(isset( $assets['libs'])){
    $assets_add['libs'] = $assets['libs'];
  }
  if(isset( $assets['js'])){
    $assets_add['js'] = $assets['js'];
  }
@endphp

@add_assets($assets_add) @endadd_assets

@endif

@if( isset($urls) )
  @foreach( $urls as $url  )
    @push('js')
    <script type="text/javascript">
      var {{ $url[0] }} = '{{ $url[1] }}'
    </script>
    @endpush
  @endforeach
@endif

@if( isset($js) )
@push('js')
  {!! $js !!}
@endpush
@endif

{{-- bread --}}
@if( isset($bread) )
  @section('bread')
    @foreach( $bread as $link )    
      <li>    
        @if( isset($link[1]) )  
          <a href="{{ $link[1] }}"> {{ $link[0] }} </a>
        @else 
          {{ $link[0] }}
        @endif
      </li>
    @endforeach  
  @endsection
@endif

{{-- titulo del contenido --}}
@if( isset($titulo_pagina) )
  @if( isHtmlStringInstance($titulo_pagina) )
    @section( 'titulo_pagina')
      {!! $titulo_pagina !!}
    @endsection
  @else
    @section('titulo_pagina', $titulo_pagina )
  @endif
@endif

{{-- titulo del contenido --}}
@if( isset($titulo_small) )
@if( isHtmlStringInstance($titulo_small) )
@section( 'titulo_small')
{!! $titulo_small !!}
@endsection
@else
@section('titulo_small', $titulo_small )
@endif
@endif



{{-- contenido --}}
@if( isset($contenido))
  @section('contenido') 
    {!! $contenido !!}
  @endsection
@endif

{{-- contenido_footer --}}
@if( isset($contenido_footer))
  @section('contenido_footer') 
    {!! $contenido_footer !!}
  @endsection
@endif

{{-- contenido_footer --}}
@if( isset($footer_before))
  @section('footer_before') 
    {!! $footer_before !!}
  @endsection
@endif