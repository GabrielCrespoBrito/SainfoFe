@if($model->VtaOper)
  @php
    if($model->isOrdenCompra() ){
      $route = route('compras.show' , $model->VtaOper);
      $docNume = $model->compra->CpaNume;
    }
    else {
      $route = route('ventas.show' , $model->VtaOper);
      $docNume = $model->venta->VtaNume;
    }
  @endphp

  <a target="_blank" href="{{  $route }}" class="btn btn-xs btn-default"> {{ $docNume }} </a>
@else 
  -
@endif