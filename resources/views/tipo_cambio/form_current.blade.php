<form id="formTC" method="post" action="{{ route('tipo_cambio.updatedToday') }}">  
    
  @method('post')
  @csrf
  
  <div class="row">
    
    <div class="col-md-6">
      <div class="input-group">
      <span class="input-group-addon">Venta </span>
      <input class="form-control input-tc input-venta" type="text" name="TipVent" data-type="venta" value="{{ $tcVenta }}">
      </div>
    </div>

    <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Compra </span>
      <input class="form-control input-tc input-compra" type="text" name="TipComp" data-type="compra" value="{{ $tcCompra }}">
    </div>
    </div>

  </div>

  <div class="text-center info-tc"> 
    <span class="tc-explicacion">  Ultimo tipo de cambio extraido de la sunat  </span>
    <span class="fecha-tc"> {{ $ultimaTcExtraido }} </span>
    <span class="tc-value">

      <span class="value tc-venta"> 
        <span class="text">Venta:</span> 
        <span clas="number current-tc" data-type="venta"> {{ $tcSunatVenta }} </span>  
      </span>
      
      <span class="value tc-compra"> 
        <span class="text">Compra:</span> 
        <span clas="number current-tc" data-type="compra"> {{ $tcSunatCompra }}</span> 
      </span>


    </span>

    <span id="copy-tc" title="copiar" class="btn btn-xs btn-default">
      <span class="fa fa-copy"></span>
    </span>

  </div>

  <br>
  
  <button class="btn btn-default"> <span class="fa fa-save"> </span> Guardar </button>
  
  <a href="https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias" target="_blank" class="btn btn-primary pull-right">
    <span class="fa  fa-external-link"> </span>  Sunat </a>
  
</form>