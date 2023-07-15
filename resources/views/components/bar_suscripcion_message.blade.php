@php
  $className = $datesEmpresa->isVencida ? 'vencida' : 'porvencer';
@endphp

@component('components.bar_notificacion', ['className' => "suscripcion {$className}"  ])
  @slot('content')

    <div class="message-content"> 


      @if( $datesEmpresa->isVencida )
      Su sucripcion se encuentra vencida desde el <strong> {{ $datesEmpresa->fechaVencimiento }}, para renovar presione el enlace 
      </strong> <a target="_blank" href="{{ route('suscripcion.planes.index') }}" class="enlace"> Ver mas </a>

      @else
        Le quedan <strong> {{ $datesEmpresa->diasParaVencimiento }} </strong> del vencimiento de su suscripción el <strong> {{ $datesEmpresa->fechaVencimiento }}</strong>, para renovar presione el enlace <a target="_blank" href="{{ route('suscripcion.planes.index') }}" class="enlace"> Ver mas </a>

      @endif
  
  </div> 

  @endslot
@endcomponent
