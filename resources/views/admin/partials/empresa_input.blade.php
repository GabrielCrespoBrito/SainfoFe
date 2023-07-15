  <div class="row">
    <div class="form-group col-md-12">
      <label> Empresa </label>
      <input type="hidden" value="{{ $empresa->id() }}" name="empresa_id">
      <input class="form-control" type="text" disabled value="{{ $empresa->getNombreFormato() }}">
    </div>
  </div>
