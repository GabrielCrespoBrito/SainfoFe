<div class="totales">

	<div class="row info_principal">

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Cantidad </span>
        <input name="" readonly="readonly" class="form-control input-sm" data-total="Cantidad" value="{{ $compra->DetCant }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Peso </span>
        <input name="" readonly="readonly" class="form-control input-sm" data-total="Peso" value="{{ $compra->DetPeso }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Sub Total </span>
        <input name="" readonly="readonly" class="form-control input-sm total-base" data-total="SubTotal" value="{{ $compra->subTotal() }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">IGV </span>
        <input name="" readonly="readonly" class="form-control input-sm total-igv" data-total="IGV" value="{{ $compra->CpaIGVV }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Total </span> 
        <input name="" readonly="readonly" class="form-control input-sm total-importe" data-total="Total" value="{{ $compra->CpaImpo }}" type="text">
      </div>
    </div>    

	</div>

</div>