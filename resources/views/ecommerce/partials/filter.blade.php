<div class="row" style="margin-bottom: 25px">

  <div class="col-md-4">
  <select id="selectUrl" name="order-status" class="form-control">
      <option {{ $status == 'ywraq-new' ? 'selected' : '' }} value="ywraq-new"> Pendiente </option>  
      <option {{ $status == 'completed' ? 'selected' : '' }} value="completed"> Procesado </option>
  </select>
  </div>

</div>