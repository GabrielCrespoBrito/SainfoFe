<div class="modal modal-seleccion fade" id="modalSelectCotizacion">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>

      <h4 class="modal-title">Listado de Cotizaciones/Preventas Pendientes por Facturar</h4>
    </div>
    <div class="modal-body">
      <div class="row">

      <div class="botones_div col-md-4">
        <div class="form-group">
          <select name="tidcodi_coti" class="form-control">
            <option value="50"> Cotización </option>
            <option value="53"> Preventa </option>
          </select>
        </div>
      </div >
        
     {{--  --}}

    <div class="col-md-4 col-sm-4  col-xs-4 no_p">
      <select name="local" class="form-control">
        @foreach ($locales as $local)
        <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>Local - {{ optional($local->local)->LocNomb }} </option>
        @endforeach
      </select>
    </div>
      {{-- --}}

      <div class="botones_div col-md-4">
        <a class="btn pull-left btn-success btn-flat pull-right elegir_elemento">
          <span class="fa fa-check"> </span> Aceptar</a>
      </div>



      </div>


      <div class="factura_select">

        <table class="table table-responsive table-bordered sainfo-table" id="datatable-cotizacion_select" style="width: 100%">
        <thead>
          <tr>
            <td> Código </td>
            <td> Fecha emisión </td>
            <td> Ruc </td>    
            <td> Razon social </td>              
            <td> Moneda </td>                        
            <td> Importe </td>
            <td> Usuario </td>              
          </tr>
        </thead>
        <tbody></tbody>
        </table>
      </div>
    </div>

  </div>

  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

