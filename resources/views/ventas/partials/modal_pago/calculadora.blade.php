<!-- calculadora -->
<div class="calculadora active">

  <div class="title"> Calculadora</div>

  <div class="row">
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Soles:</span>
        <input class="form-control input-sm" type="number" min="0" value="0" default-value="0" data-db="soles" name="soles" >      
      </div>
    </div>
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Dolares:</span>
        <input class="form-control input-sm" type="number" min="0" value="0" default-value="0" data-db="dolar" name="dolar" >      
      </div>
    </div>            
  </div>

  <div class="row">
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Saldo Documento:</span>
        <input class="form-control input-sm " data-db="VtaImpo" disabled="disabled" name="VtaImpo" data-field="total">
      </div>
    </div>
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">T.Cambio</span>
        <input class="form-control input-sm" type="number" min="0" value="0" default-value="0" data-field="tipocambio" data-db="VtaTcam" name="VtaTcam" >      
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Total recibo</span>
        <input class="form-control input-sm " data-db="totalRecibido" name="totalRecibido" >      
      </div>
    </div>

    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon"><span id="estado_pago">VUELTO</span></span>
        <input class="form-control input-sm " data-db="totalOperacion" name="totalOperacion" >      
      </div>
    </div>
  </div>

</div>
<!-- /calculadora -->