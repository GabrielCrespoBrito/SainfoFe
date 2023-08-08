<div class="row">  

  <div class="form-group col-md-3 no_pr">    
    <div class="input-group">
      <span class="input-group-addon">Nro</span>
        <input class="form-control input-sm keyjump" name="CpaOperNext" type="text" readonly="readonly" value="{{ $create ? $produccion->getNextId() : $produccion->manId }}">     
    </div>
  </div>

  <div class="form-group col-md-3">    
    <div class="input-group">
      <span class="input-group-addon">Estado</span>
        <input class="form-control input-sm keyjump" name="" type="text" readonly="readonly" value="{{ $create ? '' : $produccion->presenter()->getReadEstado() }}">     
    </div>
  </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Fecha Emisión </span>
      <input name="manFechEmis" data-fecha_inicial="{{ $produccion->manFechEmis }}" {{ setInputState($show) }} required="required" value="{{  $create ? date('Y-m-d') : $produccion->manFechEmis  }}" class="form-control input-sm" type="date">              
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Fecha Vencimiento </span>
      <input name="manFechVenc" data-fecha_inicial="{{ $produccion->manFechVenc }}" {{ setInputState($show) }} required="required" value="{{  $create ? date('Y-m-d') : $produccion->manFechVenc }}" class="form-control input-sm" type="date">              
      </div>
    </div>

</div>