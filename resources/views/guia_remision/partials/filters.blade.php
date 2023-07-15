@php
// 2.0
$format = $format ?? "0";
$mes = $mes ?? date('Ym');
$tipo_documento = $tipo_documento ?? null;
$isSalida = $isSalida ?? true;
@endphp

<div class="row">


  <input type="hidden" name="tipo_documento" value="{{ $tipo_documento }}" >

  <div class="col-md-2 acciones-div">
    @component('components.specific.select_mes' , ['alloption' => true , 'mes' => $mes ]) @endcomponent
  </div>

  <div class="col-md-2 acciones-div p-0">
    <select class="form-control input-sm" name="formato">
      <option value="1" {{ $format == '1' ? 'selected' : '' }}> Formato </option>
      <option value="0" {{ $format == '0' ? 'selected' : '' }}> Sin Formato </option>
    </select>
  </div>

  <div class="col-md-2 col-sm-6  col-xs-6 no_p">
    <select name="local" class="form-control input-sm">
      @foreach ($locales as $local)
      <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>Local - {{ optional($local->local)->LocNomb }} </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6 acciones-div">
    <a 
      href="{{ $routeCreate  }}"
      data-issalida="{{ $isSalida }}"
      class="btn new-guia btn-primary btn-flat pull-right pendientes"> 
      <span class="fa fa-plus"></span> Nueva </a>

  </div>

</div>