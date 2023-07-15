@php
  // $dateEmpresa = get_empresa()->getSuscripcionDates();
  $className = $dateEmpresa->isVencida ? 'vencida' : 'porvencer';
@endphp

@component('components.bar_notificacion', ['className' => 'suscripcion ' . $className ])
  @slot('content')
    {{ $dateEmpresa }}
  @endslot
@endcomponent