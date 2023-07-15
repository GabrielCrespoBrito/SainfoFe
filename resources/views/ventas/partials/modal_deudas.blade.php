<div class="modal modal-seleccion fade" id="modalDeudas">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger no_seguir_operacion" data-dismiss="modal" aria-label="Close"> Cancelar </button>
        <button type="button" class="btn btn-success seguir_operacion"> Continuar </button>
        <h4 class="modal-title">Deudas del cliente</h4>
      </div>
      <div class="modal-body">

      <div class="row">
        <div class="form-group col-md-4 no_pr">  
          <div class="input-group">
            <span class="input-group-addon">Rázón social</span>
              <input class="form-control input-sm" data-n="PCCodi" name="razon_social" type="text" readonly="readonly" >     
          </div>
        </div>

        <div class="form-group col-md-5 no_pl">  
          <input class="form-control input-sm" data-n="PCNomb" name="razon_social" type="text" readonly="readonly">   
        </div>
              
        <div class="form-group col-md-3 no_pl">  
          <div class="input-group">
            <span class="input-group-addon">RUC</span>
              <input class="form-control input-sm" name="razon_social" data-n="PCRucc" type="text" readonly="readonly" >     
          </div>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">  
          <div class="input-group">
            <span class="input-group-addon">Linea de credito</span>
              <input class="form-control input-sm" name="razon_social" data-n="linea_credito" type="text" readonly="readonly" >     
          </div>
        </div>
              
        <div class="form-group col-md-6 no_pl">  
          <div class="input-group">
            <span class="input-group-addon">Estado Linea Cdto</span>
              <input class="form-control input-sm estado_lc" data-n="estado_linea" type="text" readonly="readonly" >
          </div>
        </div>
      </div>

   
      <div class="deudas_div">

        <table class="table table-responsive table-bordered sainfo-table" id="table_deuda" >
        <thead>
          <tr>
            <td> Nro Doc </td>
            <td> Fecha Emis </td>
            <td> Fecha Vto </td>              
            <td> Moneda </td>              
            <td> Importe </td>              
            <td> Pagado </td>              
            <td> Saldo </td>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
        </table>
      </div>

        <div class="totales_deuda">
          <div class="row">
            <div class="form-group col-md-6">  
              <div class="input-group">
                <span class="input-group-addon">Total Deuda: S/</span>
                  <input class="form-control input-sm" name="razon_social" data-n="deuda_s" type="text" readonly="readonly" >     
              </div>
            </div>
                  
            <div class="form-group col-md-6 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">US$</span>
                  <input class="form-control input-sm" name="razon_social" data-n="deuda_d" type="text" readonly="readonly" >
              </div>
            </div>
          </div>

<!-- end row -->
      </div>
    </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>