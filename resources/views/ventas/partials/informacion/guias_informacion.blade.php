{{-- getRouteEdit --}}
<div class="seccion seccion-guias">
  <div class="title-section"> Guìas </div>
  <div class="col-md-12">
    @if( $venta->guias_relacionadas->count())
      @foreach( $venta->guias_relacionadas as $guia )
        <a target="_blank"  href="{{ $guia->getRouteEdit()}}" class="btn btn-xs btn-default btn-block">
          {{ $guia->getNombre() }}
        </a>
      @endforeach

      @if( !$venta->enviosGuiaCerrado() )
        <a href="#" class="btn btn-xs btn-primary btn-block asignarguia"> <span class="fa fa-plus "></span> Agregar guia </a>
      @endif
    @else    
      @php
        $guias_ventas = $venta->guias_ventas->load('guia');
      @endphp
      @if( $guias_ventas->count() )
        @foreach( $guias_ventas as $guia  )
          <a target="_blank"  href="{{ $guia->getRouteEdit() }}" class="btn btn-xs btn-default btn-block">
            {{ $guia->guia->numero() }}
          </a>
        @endforeach
      @else
      <span class="message no-guias"> No tiene guias este documento </span>
      <a href="#" class="btn btn-xs btn-primary btn-block asignarguia"> <span class="fa fa-plus "></span> Agregar guia </a>
      @endif
    @endif
  </div>
</div>