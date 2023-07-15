<div>

  <div class="col-md-2 no_pl">
    <label>Tipo Guia</label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="tipo_guia">
      <option value="I"> Ingreso </option>
      <option value="S"> Salida </option>
    </select>
  </div>

  <div class="col-md-3 no_pl">
    <label>Estado Sunat</label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="estado_sunat">
      <option value=""> -- TODOS -- </option>
      <option value="0"> Enviados </option>
      <option value="9"> Pendientes </option>
      <option value="2"> Anulados </option>
    </select>
  </div>

  <div class="col-md-2 no_p col-sm-6  col-xs-6">
    <label> Fecha inicio </label>
    <input type="text" value="{{ $fecha_inicio }}" autocomplete="off" name="fecha_desde" class="form-control flat input-sm datepicker no_br text-center">
  </div>

  <div class="col-md-2 no_p col-sm-6  col-xs-6">
    <label> Fecha final </label>
    <input type="text" value="{{ $fecha_final }}" autocomplete="off" name="fecha_hasta" class="form-control flat input-sm datepicker no_br text-center">
  </div>

  <div class="col-md-3 col-sm-6  col-xs-6">
    <label> Estado Guia </label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="formato_guia">
      <option value="1"> Con Formato </option>
      <option value="0"> Sin Formato </option>
    </select>
  </div>

</div>
