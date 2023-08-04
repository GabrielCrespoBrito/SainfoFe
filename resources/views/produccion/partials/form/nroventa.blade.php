<div class="row">  

  <div class="form-group col-md-3 no_pr">    
    <div class="input-group">
      <span class="input-group-addon">Nro venta</span>
        <input class="form-control input-sm keyjump" name="CpaOperNext" type="text" readonly="readonly" value="{{ $create ? $compra->CpaOperNext : $compra->CpaOper }}">     
    </div>
  </div>

  <div class="form-group col-md-4 no_pr">  
    <div class="input-group">
    <span class="input-group-addon">Tipo documento</span>
        <select name="TidCodi" {{ setInputState($show) }} class="form-control input-sm keyjump">          
          <option value="01" {{  setSelectedOption( $compra->TidCodi , "01")  }}> FACTURA </option>
          <option value="03" {{  setSelectedOption( $compra->TidCodi , "03")  }}> BOLETA </option>
          <option value="07" {{  setSelectedOption( $compra->TidCodi , "07")  }}> NOTA DE CREDITO </option>
          <option value="40" {{  setSelectedOption( $compra->TidCodi , "40")  }}> COMPRA LIBRE </option>
        </select>
    </div>
  </div>

  <div class="form-group col-md-5">  
    <div class="input-group">
      <span class="input-group-addon">Nro documento</span>
      <span class="input-group-addon padding-none">
        <input name="CpaSerie" placeholder="Serie" maxlength="4" {{ setInputState($show) }} class="form-control  text-uppercase input-sm keyjump" value="{{ $compra->CpaSerie }}">
      </span>
      
      <span class="input-group-addon padding-none">
        <input name="CpaNumee" placeholder="Correlativo" maxlength="8" {{ setInputState($show) }} class="form-control text-uppercase input-sm keyjump" value="{{ $compra->CpaNumee }}">
      </span>
    </div>
  </div>

</div>