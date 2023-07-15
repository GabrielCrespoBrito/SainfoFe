<form class="form">

<!-- row -->
<div class="row">
  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Moneda</span>
        <select class="form-control input-sm" data-field="MonCodi" name="moneda" type="text">     
          <option value="01">SOLES</option>
          <option value="02">DOLAR</option>
        </select>
    </div>
  </div>

  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Fecha</span>
        <input class="form-control datepicker input-sm" data-field="MocFech" name="fecha" value="{{ date('Y-m-d') }}" data-default="{{ date('Y-m-d') }}" type="text">     
    </div>
  </div>
</div>
<!-- /row -->

  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Motivo</span>
        <select placeholder="Elegir Motivo" data-settings="{{ json_encode(['minimuminputlength']) }}" data-minimuminputlength="0" class="form-control input-sm select2" data-url="{{ route('cajas.motivos_search' , 'I') }}" data-text="" data-id="" data-field="EgrIng" name="motivo" style="display:none;position:absolute">
        </select>
        <div class="input-group-addon">
          <a target="_blank" href="{{ route('cajas.motivos_show' , 'ingresos') }}" class=""> <i class="fa fa-plus"></i> </a>
        </div>
      </div>
    </div>
  </div>

<!-- /row -->
<div class="row">
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Nombre</span>
        <input class="form-control input-sm " name="nombre" data-field="MocNomb" value="" type="text">     
    </div>
  </div>
</div>  
<!-- /row -->

<!-- row -->
<div class="row">

  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Monto</span>
        <input class="form-control input-sm" name="monto" data-default="0.00" data-field="CANINGS" value="0.00" type="number">     
    </div>
  </div>

  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Otro Doc.:</span>
        <input class="form-control input-sm" name="otro_doc" data-field="OTRODOC" value="" type="text">     
    </div>
  </div> 

</div> 
<!-- /row -->

<!-- row -->
<div class="row">
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Autoriza:</span>
        <input class="form-control input-sm" data-field="AUTORIZA" data-default="{{ auth()->user()->usunomb }}" name="autoriza" value="{{ auth()->user()->usunomb }}" type="text">     
    </div>
  </div>
</div>  
<!-- /row -->

</form>