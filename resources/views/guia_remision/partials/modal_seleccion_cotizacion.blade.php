<div class="modal modal-seleccion fade" id="modalSelectCotizacion">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Lista de cotizaciones pendientes de facturar</h4>
      </div>
      <div class="modal-body">
        <div class="botones_div">

        <a class="btn pull-left btn-success btn-flat elegir_elemento">
          <span class="fa fa-check"> </span> Aceptar</a>

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
</div>