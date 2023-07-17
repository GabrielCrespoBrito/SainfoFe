<div class="reportes">

<!-- Fechas -->
<div class="row">

  {{-- Mes --}}
  <div class="col-md-12 ">

    <div class="filtro" style="padding:0" id="condicion">
      <fieldset class="fsStyle">
        <legend class="legendStyle">Exporte Reporte</legend>
        <div class="row" id="demo">
          <div class="col-md-8" >
            <div>
              <select name="formato" class="form-control">
                <option value="pdf"> PDF </option>
                <option value="excell"> Excell </option>
              </select>
            </div>
          </div>

          <div class="col-md-4 ">
            <a href="#" data-url="{{ route('reportes.ventas_mensual_pdf') }}" class="btn btn-flat btn-success btn-flat btn-block generate-report"> <span class="fa fa-download"></span> Reporte </a>
          </div>

        </div>
      </fieldset>
    </div>
  </div>
  {{-- Mes --}}


</div>
<!-- Fechas -->

</div>
