<div class="row">

  <div class="col-md-12">

    <div class="form-group col-md-4 no_p">  
      <div class="input-group">
        <span class="input-group-addon">Fecha emisión </span>
      <input name="CpaFCpa" data-fecha_inicial="{{ $compra->CpaFCpa }}" {{ setInputState($show) }} required="required" class="form-control input-sm datepicker keyjump z-index-1" data-event="datepicker" value="{{ $compra->CpaFCpa }}" type="text">              
      </div>
    </div>

    <div class="form-group col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">Fecha contable </span>
        <input name="CpaFCon" {{ setInputState($show) }} required="required" class="form-control input-sm datepicker keyjump z-index-1" data-event="datepicker" value="{{ $compra->CpaFCon }}" type="text">              
      </div>
    </div>

    <div class="form-group col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">Fecha vencimiento </span>
        <input name="CpaFven" {{ setInputState($show) }} required="required" class="form-control input-sm datepicker keyjump z-index-1" data-event="datepicker" value="{{ $compra->CpaFven }}" type="text">              
      </div>
    </div>

  </div>

</div>
