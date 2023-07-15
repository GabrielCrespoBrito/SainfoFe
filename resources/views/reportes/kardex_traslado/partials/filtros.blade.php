<div class="filtros">
  
  <form class="formReporte" action="{{ route('reportes.kardex_traslado_report') }}" method="post">  
  @csrf
    <!-- Fechas -->
    <div class="filtro" id="condicion">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Fechas (desde,hasta)</legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <input type="text" value="{{ old('fecha_inicio', $fecha_actual )  }}" autocomplete="off" requred name="fecha_inicio" class="form-control input-sm datepicker no_br flat text-center">


            </div>

            <div class="col-md-6">
              <input type="text" value="{{ old('fecha_final', $fecha_actual ) }}" autocomplete="off" requred name="fecha_final" class="form-control input-sm datepicker no_br flat text-center">

            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <!-- Fechas -->

    <!-- Almacenes -->
    <div class="filtro">
      <div class="cold-md-12">
        <fieldset class="fsStyle">        
          <legend class="legendStyle">Almacen Origen y Destino </legend>

          <div class="row" id="demo">
            
            <div class="col-md-6">
              <select type="text" requred name="local_origen" class="form-control input-sm flat text-center">
                @foreach( $locales as $local )
                  <option {{ old('local_origen') == $local->LocCodi ? 'selected' : '' }} value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <select type="text" requred name="local_destino" class="form-control input-sm flat text-center">
                @foreach( $locales as $local )
                  <option {{ old('local_destino') == $local->LocCodi ? 'selected' : '' }} value="{{ $local->LocCodi }}">{{ $local->LocNomb }}</option>
                @endforeach
              </select>
            </div>

          </div>
        </fieldset>
      </div>
    </div>
    <!-- Almacenes -->

    <!-- Almacenes -->
    <div class="filtro">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Tipo de Reporte </legend>
          <div class="row" id="demo">
            <div class="col-md-12">
              <select type="text" requred name="tipo_reporte" class="form-control input-sm flat text-center">
                <option value="pdf" {{ old('tipo_reporte' , 'pdf') == 'pdf' ? 'selected' : '' }}> PDF </option>
                <option value="excell" {{ old('tipo_reporte' , 'excell') == 'excell' ? 'selected' : '' }}> Excell </option>
              </select>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <!-- Almacenes -->

</div>

<div class="row">
  <div class="col col-md-12">
    <button type="submit" class="btn btn-primary btn-flat "> Aceptar </button>
    <a href="{{ route('home') }}" class="btn btn-danger btn-flat  pull-right "> Salir </a>
  </div>
</div>

</form>

</div>