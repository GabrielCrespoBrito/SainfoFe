@php 
  $isAdmin = $isAdmin ?? false;
@endphp

<div class="orden-show">

  <div class="row">
    <div class="col-md-12">
      @component('components.box', ['className' => 'box-notificacion box-shadow box-border suscripcion-title' ])
        @slot('header')
    {{ $suscripcion->orden->planduracion->nombreCompleto() }} <a href='{{ route('suscripcion.planes.index') }}' class='cambiar'> Renovar / Potenciar </a>
        @endslot

      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      @include('suscripcion.partials.utilizacion')
    </div>

    <div class="col-md-6">
      @include('suscripcion.partials.parametros')
    </div>

  </div>
</div>