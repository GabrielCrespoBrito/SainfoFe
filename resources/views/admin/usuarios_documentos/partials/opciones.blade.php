<div class="row">
  <div class="form-group col-md-6">
    <label style="margin-right:10px"> <input type="checkbox" name="defecto" {{ $usuario_documento->defecto == "1" ? 'checked=checked' : '' }} value="1">Por defecto</label>
    <label style="margin-right:10px"> <input type="checkbox" name="estado" {{ $usuario_documento->estado == "1" ? 'checked=checked' : '' }} value="1">Estado</label>
    <label> <input type="checkbox" name="contingencia" {{ $usuario_documento->contingencia == "1" ? 'checked=checked' : '' }} value="1">Contingencia</label>
  </div>
</div>