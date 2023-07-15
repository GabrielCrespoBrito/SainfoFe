@php
  $id = $id ?? '';
  $clientes = collect($clientes);
  $time = $time ?? 200000;
@endphp

{{-- https://sainfo.pe/admin/resumenes --}}
<div id="{{ $id }}" class="carousel carousel-sainfo slide d-md-block" data-ride="carousel" data-interval="{{ $time }}">
    <div class="carousel-inner">           
      @foreach( $clientes->chunk(5) as $cliente_group )
      <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
        <div class="row">
          @foreach( $cliente_group as $cliente )
              <div class="{{ $loop->first ? 'offset-sm-1' : '' }} col-md-2 div-item">
              <div class="info">
                <div class="info-slot info-rs">
                  @if(isset($cliente['sitio']))
                  <a target="_blank" href="{{ $cliente['sitio'] }}"> {{ $cliente['razon_social'] }} </a>
                  @else
                  {{ $cliente['razon_social'] }}
                  @endif
                </div>
                <div class="info-slot info-ruc">{{ $cliente['ruc'] }}</div>
              </div>
                <a target="_blank" href="{{ isset($cliente['sitio']) ? $cliente['sitio'] : '#' }}">
                  <img class="d-block w-100" src="{{ $cliente['path'] }}" alt="First slide">
                </a>
              </div>        
            @endforeach
          </div>
        </div>
        @endforeach
    </div>
</div>