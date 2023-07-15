<ul>
  @foreach( $plan->caracteristicas as $caracteristica )
  
    <li>
      <span class="valor valor-{{ $caracteristica->value }}">
        {{ $caracteristica->value }}
      </span>
      <span class="nombre">
        {{ $caracteristica->nombre }}
      </span>
      @if( $caracteristica->message )
        <span title="{{ $caracteristica->message }}" class="fa fa-info-circle"></span>
      @endif

    </li>
  @endforeach
</ul>