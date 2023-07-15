@php
  $duraciones = $plan->duraciones->where('empresa_id', empcodi());
@endphp

<div class="col-md-{{ $colSize }}">

  <!--PRICE CONTENT START-->
  <div class="generic_content {{ $esPlanActual ? 'current-plan' : '' }} {{ $isElegible ? '' : 'no-elegible' }} clearfix">

    <!--HEAD PRICE DETAIL START-->
    <div class="generic_head_price clearfix">

      <!--HEAD CONTENT START-->
      <div class="generic_head_content clearfix">

        <div style="display:none" class="ribbon text-center"> <span class="descuento">10%</span> <br> <span class="descripcion">Desc</span> </div>

        <!--HEAD START-->
        <div class="head_bg"></div>
        <div class="head">
          <span> {{ $plan->nombre }}  </span>
        </div>
        <!--//HEAD END-->

      </div>
      <!--//HEAD CONTENT END-->

      <!--PRICE START-->
      <div class="generic_price_tag clearfix">
        <span class="price">
          <span class="sign">S./</span>
          <span class="currency">00</span>
          <span class="cent">.00</span>
          <span class="month"></span>
        </span>
      
      <div class="duracion">
        <select class="plan-duracion">            
          @foreach( $duraciones as $duracion )
            <option value={{ $duracion->id }} data-info="{{ $duracion }}"> {{ $duracion->duracion->nombre }} </option>
          @endforeach 
        </select> 
      </div>
      
      </div>
      <!--//PRICE END-->

    </div>
    <!--//HEAD PRICE DETAIL END-->

    <!--FEATURE LIST START-->
    <div class="generic_feature_list">
      <ul>

      @php      
        $caracteristicas = $duraciones->first()->caracteristicas;
      @endphp

        @foreach( $caracteristicas as $caracteristica )
          <li 
            data-value="{{ $caracteristica->value }}"
            data-nombre="{{ $caracteristica->caracteristica->nombre }}">          
            <span class="valor valor-{{$caracteristica->value}}"> {{ $caracteristica->value }} </span>
            <span class="nombre"> {{ $caracteristica->caracteristica->nombre }} </span>
            @if( $caracteristica->caracteristica->hasMessage() )
              <span data-toggle="tooltip" title="{{ $caracteristica->caracteristica->adicional }}" class="fa fa-info-circle"> </span>
            @endif
          </li>
        @endforeach 

      </ul>
    </div>
    <!--//FEATURE LIST END-->

    <!--BUTTON START-->
    <div class="generic_price_btn clearfix">
      <a class="link-confirm" data-href="{{ route('suscripcion.planes.confirm', '@@') }}" href="#">
      @if( $esPlanActual )
        @if( $isElegible )
          Renovar
        @else
          Actual
        @endif
      @else
        @if( $isElegible )
          Elegir Plan
        @else
          Elegir No Disponible
        @endif
      @endif
    </a>
    </div>
    <!--//BUTTON END-->

  </div>
</div>