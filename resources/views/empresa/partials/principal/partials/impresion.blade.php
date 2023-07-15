  {{-- <div class="row">

    <div class="form-group col-md-2">
      <div class="checkbox">
        <label> <input type="checkbox" name="imprimir" value="1" {{ $empresa->FE_ENVIO == "1" ? 'checked=checked' : '' }}> Imprimir directo</label>
      </div>
    </div>

    <div class="form-group {{ $errors->has('nombre_impresora') ? 'has-error' : '' }} col-md-8">
      <div class="input-group">
        <span class="input-group-addon">Nombre Impresora <span data-toggle="tooltip" title="asdasd" class="fa fa-info-circle"> </span> </span>
        <input class="form-control input-sm" name="nombre_impresora" type="text" value="{{ old('nombre_impresora', $empresa->FE_RPTA  ) }}">
      </div>
    </div>

  <div class="form-group {{ $errors->has('cant_copias') ? 'has-error' : '' }} col-md-2">
    <div class="input-group">
      <span class="input-group-addon">Copias</span>
      <input class="form-control input-sm" min="1" type="number" name="cant_copias" value="{{ old('cant_copias', $empresa->EmpReten  ) }}">
    </div>
  </div>

</div> --}}