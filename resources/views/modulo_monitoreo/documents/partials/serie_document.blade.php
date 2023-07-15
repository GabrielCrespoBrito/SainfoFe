@php
  $todos = $todos ?? true;    
  $name = $name ?? 'serie_id';
  $size = $size ?? 'col-md-6';
  $withLabel = $withLabel ?? false;
@endphp

<div class="{{ $size }} form-group">
    @if( $withLabel )
      <label for=""> Serie </label>
    @endif
    
    <select name="{{ $name  }}" class="form-control input-sm">

      @foreach ($series as $serie )
        <option value="{{ $serie->id }}">{{ $serie->descripcionFull() }}</option>
      @endforeach

    </select>
  </div>


