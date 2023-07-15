import React from 'react';

export default function FieldsetForm(props) {
  return (
    <div className="filtro" id="condicion">
      <div className="cold-md-12">
        <fieldset className="fsStyle">
          <legend className="legendStyle"> {props.title}</legend>
          <div className="row">
            {props.children}
          </div>
        </fieldset>
      </div>
    </div>
  );
}


/*  

@php
  $locales = $locales ?? get_empresa()->almacenes;
  $hideFilterProducto = $hideFilterProducto ?? false;
  $hideCheckBox = $hideCheckBox ?? false;
  $hideBtnBack = $hideBtnBack ?? false;
  $hideBtnSave = $hideBtnSave ?? false;
  $hideForm = $hideForm ?? false;
  


@endphp

<div class="filtros">
  @if(!$hideForm)
  <form class="formReporte" action="{{ route('reportes.kardex_pdf') }}" method="post">  
  @else
  <div class="formReporte">
  @endif
    @csrf

    <!-- Fechas -->
    <div class="filtro" id="condicion">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Fechas (desde,hasta)</legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <?php $date = date('Y-m-d'); ?>
              <input type="text" value="{{ $date }}" data-default="{{ $date }}" autocomplete="off" requred name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">


            </div>

            <div class="col-md-6">
              <?php $date = date('Y-m-d'); ?>
              <input type="text" value="{{ $date }}" data-default="{{ $date }}" autocomplete="off" requred name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">


            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <!-- Fechas -->


    <!-- Articulos -->
    @if(!$hideFilterProducto)
    <div class="filtro" id="condicion">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Articulos codigo (Inicio,Final)</legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <select name="articulo_desde" requred class="form-control input-sm flat text-center">
              </select>
            </div>
            <div class="col-md-6">
              <select name="articulo_hasta" requred class="form-control input-sm  flat text-center">
              </select>

            </div>
          </div>
        </fieldset>
      </div>
    </div>
    @endif
    <!-- Articulos -->

    <!-- Almacen -->
    <div class="filtro">
      <div class="cold-md-12">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Almacen</legend>
          <div class="row" id="demo">
            <div class="col-md-6">
              <select type="text" requred name="LocCodi" class="form-control input-sm flat text-center">
                <option value="todos">---- TODOS ----</option>
                @foreach( $locales as $local )
                <option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
                @endforeach
              </select>
            </div>

            @if(!$hideCheckBox)
              <div class="col-md-6">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="articulo_movimiento" value="true" checked=""> Solo articulo con movimiento
                  </label>
                </div>
              </div>
            @endif
          </div>

        </fieldset>
      </div>
    </div>
    <!-- Fechas -->

</div>

@if(!$hideBtnSave)
<div class="row">
  <div class="col col-md-12">
    <button type="submit" class="btn btn-primary btn-flat "> Aceptar </button>
    @if(!$hideBtnBack)
      <a href="{{ route('home') }}" class="btn btn-danger btn-flat  pull-right "> Salir </a>
    @endif
  </div>
</div>
@endif


  @if(!$hideForm)
  </form >
    @else
  </div>
  @endif



  */