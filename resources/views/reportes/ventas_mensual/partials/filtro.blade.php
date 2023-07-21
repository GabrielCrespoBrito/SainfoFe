  <div class="row">

    <div class="col-md-4 text-left">
      <span class="bg-gray date-update btn-sm btn-flat btn-block" style="display:none"> 
      <span class="fa fa-calendar"></span>
      <span class="value"></span>
      </span>
    </div>


    <div class="col-md-8 text-right">
    Filtrar por:
      <a href="#" data-url="{{ route('reportes.consult_date') }}" class="btn btn-xs btn-flat btn-default active btn-filtro-change" data-tipo="mes"> <span class="fa fa-calendar"></span> Mes</a>
      <a href="#" class="btn btn-xs btn-flat btn-default btn-filtro-change" data-tipo="fecha"> <span class="fa fa-calendar"></span> Por Fechas</a>
    </div>
  </div>

  <!-- Fechas -->
  <div class="row">

    {{-- Mes --}}
    <div class="col-md-12 ">

      <div class="filtro" style="padding:0" id="condicion">
        <fieldset class="fsStyle">
          <legend class="legendStyle">Mes </legend>
          <div class="row" id="demo">
            <div class="col-md-8" >
              <div class="filtro_temporalidad" data-tipo="mes">
                @component('components.specific.select_mes', ['mes' => $mes ?? null , 'fechas' => true, 'select_option' => true ]) @endcomponent
              </div>

              <div class="filtro_temporalidad" style="display:none" data-tipo="fecha">
                <div class="col-md-6 no_p col-sm-6  col-xs-6">
                  <input type="text" data-format="Y-m-d" value="{{ date('Y-m-d') }}" name="fecha_desde" class="form-control flat input-sm datepicker no_br text-center">
                </div>
                <div class="col-md-6 no_p col-sm-6  col-xs-6">
                  <input type="text" data-format="Y-m-d" value="{{ date('Y-m-d') }}" name="fecha_final" class="form-control flat input-sm datepicker no_br text-center">
                </div>
              </div>
            </div>

            <div class="col-md-3">

              <a href="#" data-url="{{ route('reportes.ventas_mensual_getdata') }}" class="btn btn-flat btn-primary btn-flat btn-block search-consulta"> <span class="fa fa-search"></span> Consultar </a>
      
            </div>

            <div class="col-md-1">
              <input style="margin-top:.5em" data-toggle="tooltip" title="Consultar Estado en la Sunat" type="checkbox" name="consult" value="1">
            </div>


          </div>
        </fieldset>
      </div>
    </div>
    {{-- Mes --}}




  </div>
  <!-- Fechas -->