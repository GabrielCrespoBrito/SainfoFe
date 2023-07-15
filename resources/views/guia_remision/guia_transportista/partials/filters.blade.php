{{------------------------------------------------------------------------------------------------------------------}}
<div class="col-md-2 acciones-div">
  @component('components.specific.select_mes' , ['alloption' => true , 'mes' => date('Ym') ])
  @endcomponent
</div>
{{------------------------------------------------------------------------------------------------------------------}}

{{------------------------------------------------------------------------------------------------------------------}}
<div class="col-md-2 col-sm-6  col-xs-6 no_pl">
  <select name="local" class="form-control input-sm">
    @foreach ($locales as $local)
    <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>Local - {{ optional($local->local)->LocNomb }} </option>
    @endforeach
  </select>
</div>

<div class="col-md-2 acciones-div p-0">
  <select class="form-control input-sm" name="tipo_movimiento">
    <option value="S" {{ $tipo == App\GuiaSalida::SALIDA  ? 'selected' : '' }} data-route="{{ route('guia_traslado.index', ['tipo' => App\GuiaSalida::SALIDA]) }}"> Salida </option>
    <option value="I" {{ $tipo === App\GuiaSalida::INGRESO ? 'selected' : '' }} data-route="{{ route('guia_traslado.index', ['tipo' => App\GuiaSalida::INGRESO]) }}"> Ingreso </option>
  </select>
</div>

<div class="col-md-2 acciones-div pr-0 estados_salidas" style="display:{{ $salida ? ''  : 'estados_salidas' }}">
  <select class="form-control input-sm" name="estados_salidas">
    <option value=""> -- TODOS -- </option>
    <option value="{{ App\GuiaSalida::ESTADO_TRASLADO_PENDIENTE }}"> Pendiente </option>
    <option value="{{ App\GuiaSalida::ESTADO_TRASLADO_CERRADO }}"> Cerrado </option>
  </select>
</div>

<div class="col-md-2 acciones-div pr-0 estados_ingresos" style="display:{{ $salida ? 'none'  : 'inherit' }}">
  <select class="form-control input-sm" name="estados_ingresos">
    <option value=""> -- TODOS -- </option>
    <option value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE }}"> Pendiente </option>
    <option value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_ACEPTADO }}"> Aceptado </option>
    <option value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_RECHAZADO }}"> Rechazado </option>
  </select>
</div>


