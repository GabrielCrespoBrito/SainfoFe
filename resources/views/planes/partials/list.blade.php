<div id="generic_price_table">
  

  <div class="row">
    <div class="col-md-12">
      <div class="price-heading clearfix">
        <h1>  Estás inscrito en el <span class="plan-nombre"> {{ $nombrePlan }}  </span> </h1>
        <p> Elige uno de nuestros planes y sigue creciendo con {{ 'SAINFO' }} </p>
      </div>
    </div>
  </div> 

    
  <!--BLOCK ROW START-->
  <div class="row">
    @php
      $colSize = 3;
    @endphp

    @foreach( $planes as $plan )
      
      @if( $plan->is_demo )
        @if( $plan_current->isDemo() )
          @include('planes.partials.plan_item', ['colSize' => $colSize, 'esPlanActual' => true , 'isElegible' => false ])
        @else
          @php
            $colSize = 4    
          @endphp
          @continue
        @endif

      @else
        @include('planes.partials.plan_item', ['colSize' => $colSize,  'esPlanActual' => $plan->id == $plan_current->id , 'isElegible' => true ])
      @endif
    @endforeach
    
  </div>
  <!--//BLOCK ROW END-->

</div>