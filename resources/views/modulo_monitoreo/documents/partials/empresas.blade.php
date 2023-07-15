@php
  $size = $size ?? 'col-md-12';
  $showButtonDocument = $showButtonDocument ?? false;
@endphp

<div class="form-group {{ $size }}" style="padding-left:0">
  <select name="empresa_id" class="form-control">
    @foreach ($empresas_mod as $empre)
      <option {{ $empre->id == $empr->id ? 'selected=selected' : '' }}  value="{{ route($routeName, $empre->id ) }}">{{ $empre->nameDocument }}</option>
    @endforeach
  </select>
</div>

@if( $showButtonDocument )
  <div class="form-group col-md-2">
    <a target="_blank" href="{{ route( $routeButton, $empr->id) }}" class="btn btn-default btn-sm btn-flat"> Documentos  </a>
  </div>
@endif
