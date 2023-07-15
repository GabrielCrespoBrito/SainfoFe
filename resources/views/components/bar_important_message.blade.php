@if( ! $datesEmpresa->isSafeTime )
  @include('components.bar_suscripcion_message', [ 'datesEmpresa' => $datesEmpresa ])
@endif