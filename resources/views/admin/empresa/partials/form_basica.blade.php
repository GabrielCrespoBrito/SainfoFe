<div class="row">

  <div class="form-group col-md-3">
    <select class="form-control input-sm" name="fe_ambiente" type="text">
      <option value="1" {{ $empresa->isActive() ? 'selected=selected' : '' }}>ACTIVA</option>
      <option value="0" {{ $empresa->isActive() ? '' : 'selected=selected' }}>INACTIVA</option>
    </select>
  </div>

  <div class="form-group col-md-4">
    <div class="input-group no-border">
      <span class="input-group-addon">Fecha Vencimiento Plan </span>
      <input class="form-control input-sm" disabled name="fe_ambiente" type="text" value="{{ $empresa->end_plan }}">
    </div>
  </div>

</div>

