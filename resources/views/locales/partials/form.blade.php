@php
$isCreate = $accion == "create";
$route = $isCreate ? route('locales.store') : route('locales.update', $model->LocCodi );

@endphp

@include('partials.errors_html')

<form accept-charset="UTF-8" class="form-locales" method="post" action="{{ $route }}">
  @csrf

  @if( !$isCreate )
  @method('PUT')
  @endif
  {{-- Si yo bajo, ABCEFGHJULJMSQTYUQWER  --}}
  @include('locales.partials.part_local')

    <div class="col-md-3">
    @include('locales.partials.part_user')
    </div>

    <div class="col-md-6">
    @include('locales.partials.part_series')
    </div>

  <div class="col-md-3">
    @include('locales.partials.part_lista_precios')
  </div>

  </div>


  </div>

  <hr>

  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-primary btn-save btn-flat"> Guardar </button>
      <a style="padding-left:10px" class="pl-2 link-index" href="{{ route('locales.index') }}"> Cancelar </a>
    </div>
  </div>



</form>
