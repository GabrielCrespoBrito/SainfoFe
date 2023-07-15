<div>

  <div class="col-md-2 no_pl">
    <label>Tipo Documento</label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="tipo_documento">
      <option value=""> -- TODOS -- </option>
      @foreach( $tipos as $code => $tipo )
      <option value="{{ $code }}"> {{ $tipo }} </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3 no_pl">
    <label>Estado Sunat</label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="estado_sunat">
      <option value=""> -- TODOS -- </option>
      @foreach( $estados as $code => $estado )
        <option  {{ $estado_sunat == $code ? 'selected' : '' }}  value="{{ $code }}"> {{ $estado }} </option>
      @endforeach
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
    <label> Estado Almacen </label>
    <select data-reload="table" class="form-control input-sm text-uppercase" name="estado_almacen">
      <option value=""> -- TODOS -- </option>
      <option value="Pe"> Pendiente </option>
      <option value="SA"> Cerrado </option>
    </select>
  </div>

</div>