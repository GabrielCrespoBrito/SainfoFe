
@php
  $locales = $locales ?? get_empresa()->almacenes;
  $hideFilterProducto = $hideFilterProducto ?? false;
  $hideCheckBox = $hideCheckBox ?? false;
  $hideBtnBack = $hideBtnBack ?? false;
  $hideBtnSave = $hideBtnSave ?? false;
  $hideForm = $hideForm ?? false;
  $fechas = fechas_reporte();
@endphp

<div class="filtros">
  <form class="formReporte" action="{{ route('reportes.kardex_by_date_create') }}" method="post">  
  @csrf

    <!-- Fechas -->
    <div class="filtro" id="condicion">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Fechas (desde,hasta)</legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <input type="text" value="{{ $fechas->inicio }}" data-default="{{ $fechas->inicio }}" autocomplete="off" requred name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">
            </div>

            <div class="col-md-6">
              <input type="text" value="{{$fechas->final }}" data-default="{{$fechas->final }}" autocomplete="off" requred name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <!-- Fechas -->

    <!-- Almacen -->
    <div class="filtro">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Almacen y Visualizaciòn de Reporte </legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <select type="text" requred name="LocCodi" class="form-control input-sm flat text-center">
                <option value="todos">---- TODOS ----</option>
                @foreach( $locales as $local )
                <option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <select type="text" requred name="tipo_reporte" class="form-control input-sm flat text-center">
                <option value="excell"> Excell </option>
                <option value="pdf"> Pdf </option>
              </select>
            </div>

          </div>
        </fieldset>
      </div>
    </div>
    <!-- Fechas -->

</div>

<div class="row">
  <div class="col col-md-12">
    <button type="submit" class="btn btn-primary btn-flat "> Aceptar </button>
    <a href="{{ route('home') }}" class="btn btn-danger btn-flat  pull-right "> Salir </a>
  </div>
</div>


</form >

