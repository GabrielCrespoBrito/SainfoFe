{{-- @dd( $notificaciones_data ) --}}

<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-bell-o"></i>
    @if($notificaciones_data->count)
    <span class="label label-danger">{{ $notificaciones_data->count }}</span>

    @endif
  </a>
  <ul class="dropdown-menu">
    <li class="header">
      @if($notificaciones_data->count)
      Tienes <strong>{{ $notificaciones_data->count }}</strong> notificaciones
      @else
      No tienes notificaciones
      @endif
    <li>

      {{-- Notificaciones --}}
      <ul class="menu ul-header">
        @foreach( $notificaciones_data->items as $notificacion )
        <li class="{{ $notificacion['type'] }}">
          <a href="{{ $notificacion['route'] }}">
            <i class="fa fa-circle-o"></i>
            {{ $notificacion['titulo'] }}
          </a>
        </li>
        @endforeach


        {{--

        <li>
          <a href="#"><i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems </a>
        </li>
        
        <li>
          <a href="#"> <i class="fa fa-users text-red"></i> 5 new members joined </a>
        </li>

        <li>
          <a href="#"> <i class="fa fa-shopping-cart text-green"></i> 25 sales made</a>
        </li>

        <li>
          <a href="#"><i class="fa fa-user text-red"></i> You changed your username</a>
        </li> 
        
        --}}

      </ul>
      {{-- /Notificaciones --}}

    </li>

    <li class="footer"><a href="{{ route('admin.notificaciones.index') }}" target="_blank">Ver Todos</a></li>
  </ul>
</li>
