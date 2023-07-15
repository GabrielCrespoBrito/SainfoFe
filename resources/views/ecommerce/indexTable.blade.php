@php
  $showFilter = true;
  $showOnlyGenerar = true;
@endphp

@if($showFilter)
  @include('ecommerce.partials.filter')
@endif

@if( !$success )
  @include('ecommerce.partials.error_coneccion') 
@else

<div id="order-show-container"> </div>

  @include('ecommerce.partials.table') 
@endif

