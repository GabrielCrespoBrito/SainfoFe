<div class="row">

  <div class="form-group col-md-8 no_pr">
    <div class="input-group">
      <span class="input-group-addon">Almacen</span>
      @if($create)
      <select {{ $active_form ? '' : 'disabled' }} name="local" class="form-control input-sm">
        @foreach ($locales as $local)
        <option data-id="{{ $local->local->codLocal() }}" value="{{ $local->loccodi }}" {{ $loccodi == $local->loccodi ? 'selected=selected' : '' }}> {{ $local->local->LocNomb }} </option>
        @endforeach
      </select>
      @else
      <input disabled type="text" value="{{ $model->local->LocNomb }}" data-loccodi="{{ $model->LocCodi }}"  data-id="{{ $model->local->codLocal() }}" class="form-control input-sm local-toma">
      @endif
    </div>
  </div>

  <div class="form-group col-md-4 no_pr">
    <div class="input-group">
      <span class="input-group-addon">Fecha</span>
      <input name="InvFech" required {{ $active_form ? '' : 'disabled' }} type="date" class="form-control input-sm " value="{{ $model->InvFech }}">
    </div>
  </div>
</div>


<div class="row mt-x">

  <div class="form-group {{ $create  ? 'col-md-12' : 'col-md-8' }}  no_pr">
    <div class="input-group">
      <span class="input-group-addon">Nombre</span>
      <input name="InvNomb" maxlength="120" required placeholder="Nombre" {{ $active_form ? '' : 'disabled' }} class="form-control input-sm " value="{{ $model->InvNomb }}">
    </div>
  </div>

  @if(!$create)
  <div class="form-group col-md-4 no_pr">
    <div class="input-group">
      <span class="input-group-addon">Estado</span>
      <p style="line-height: 2em;" class=" form-control border-radius-20 bg-{{ $model->getColorEstado() }} text-center input-sm" value="{{ $model->getNombreEstado() }}"> {{ $model->getNombreEstado() }}
      <p>
    </div>
  </div>
  @endif
</div>

<div class="row mt-x">
  <div class="form-group col-md-12 no_pr">
    <div class="input-group">
      <span class="input-group-addon">Observación</span>
      <input name="InvObse" maxlength="120" {{ $active_form ? '' : 'disabled' }} class="form-control input-sm" value="{{ $model->InvObse }}">
    </div>
  </div>
</div>