@php
$routeReporte = $routeReporte ?? route('reportes.ventas_mensual_pdf');
@endphp

<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl no_pr reportes">
    <div class="filtro" style="padding:0">

      <fieldset class="fsStyle">
        <legend class="legendStyle">Exporte Reporte</legend>
        <div class="row">

          <div class="col-md-8">
            <select name="formato" class="form-control">
              <option value="pdf"> PDF </option>
              <option value="excell"> Excell </option>
              @if( $isContador  )
              <option value="archivos"> Archivos (XML, PDFS, CDR) </option>
              @endif
              
              @if( $isFecha == false && $isContador == false )
              <option value="txt_sire"> TXT (Sire) (Se cerrada el Mes al Generar este Reporte) </option>
              @endif
            </select>
          </div>

          <div class="col-md-4">
            <a href="#" data-url="{{ $routeReporte }}"
              class="btn btn-flat btn-success btn-flat btn-block generate-report"> <span class="fa fa-download"></span>
              Reporte </a>
          </div>

        </div>
      </fieldset>

    </div>
</div>