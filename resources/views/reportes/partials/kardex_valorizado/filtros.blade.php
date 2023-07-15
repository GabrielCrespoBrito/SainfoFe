@php
@endphp

<div class="filtros">

  <!-- Mes y Almacen -->
  <div class="filtro" id="condicion">
    <fieldset class="fsStyle">
      <legend class="legendStyle">Mes y Almacen</legend>
      <div class="row" id="demo">
        <div class="col-md-6">
          @component('components.specific.select_mes')
          @endcomponent
        </div>
        <div class="col-md-6">
          <select type="text" name="local" class="form-control input-sm flat text-center">
            @foreach( $locales as $local )
            <option {{ $local->LocCodi == $localSelected ? 'selected' : '' }} value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>

            @endforeach
          </select>
        </div>
      </div>
    </fieldset>
  </div>
  <!-- Mes y Almacen -->

  <!-- Tipo de Reporte  -->
  <div class="filtro">
    <div class="cold-md-12">
      <fieldset class="fsStyle">
        <legend class="legendStyle"> Tipo Reporte y Formato </legend>
        <div class="row" id="demo">
          <div class="col-md-6">
            <select type="text" name="tipo" class="form-control input-sm flat text-center">
              <option value="detalle"> Detallado </option>
              <option value="resumen"> Resumido </option>
            </select>
          </div>

          <div class="col-md-6">
            <select type="text" name="formato" class="form-control input-sm flat text-center">
              <option value="pdf"> PDF </option>
              <option value="excell"> Excell </option>
            </select>
          </div>

        </div>
      </fieldset>
    </div>
  </div>
  <!-- /Tipo de Reporte -->

  <div class="cold-md-12">
    <label style="text-align:center;display:block"> <input type="checkbox" name="reprocesar" value="1"> Reprocesar </label>
  </div>

</div>