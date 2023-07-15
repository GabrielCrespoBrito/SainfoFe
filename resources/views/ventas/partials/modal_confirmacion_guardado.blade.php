@php
  $formato = $formato ?? '0';
  $impresion_default = $impresion_default ?? '0';
@endphp

<div class="modal modal-seleccion fade" id="modalGuardarFactura">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title">Guardar factura </h4>
      </div>
      <div class="modal-body">
        
        <div class="selec">          
          <div class="form-group">
            <form action="#">
              <div class="row">
                <div class="col-md-6">
                  <label>
                    <input value="print" name="tipo_guardado" type="checkbox" {{ $impresion_default ? 'checked=checked' : '' }}> Imprimir
                  </label>
                </div>
                <div class="col-md-6">
                  <select name="formato_impresion" class="form-control input-sm">
                    <option {{ $formato == '0' ? 'selected=selected' : '' }} value="a4"> A4 </option>
                    <option {{ $formato == '1' ? 'selected=selected' : '' }} value="a5"> A5 </option>
                    <option {{ $formato == '2' ? 'selected=selected' : '' }} value="ticket"> Ticket </option>
                  </select>
                </div>
              </div>
              
            </form>

          </div>           
        </div>

        <div class="div_guardar">

          <a href="#" id="aceptar_guardado" class="btn btn-md btn-success"> 
            <span class="fa fa-save"></span> Aceptar </a>  

          <button type="button" class="btn btn-danger btn-md pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"> <span class="fa fa-"></span> Salir </span>
          </button>

        </div>

        <div class="div_esperando" style="display: none; text-align: center;font-size: 2em;color: #999;">
          <span class="fa fa-spin fa-spinner"></span>
        </div>

      </div>

    </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
