<div class="row">  

  <div class="form-group col-md-8 no_pr">    
    <div class="input-group">
      <span class="input-group-addon">Detalles</span>
        <input class="form-control input-sm" name="manDeta" {{ setInputState($show,false) }} type="text"  value="{{ $produccion->manDeta }}">     
    </div>
  </div>

  <div class="form-group col-md-4">    
    <div class="input-group">
      <span class="input-group-addon">Responsable</span>
        <input class="form-control input-sm" name="manResp" {{ setInputState($show,false) }} type="text"  value="{{ $produccion->manResp }}">     
    </div>
  </div>

</div>