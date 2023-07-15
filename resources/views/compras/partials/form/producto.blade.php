@if( $active_form )

<div class="row producto_asignacion focus-green" id="item-add"> 
<form data-igv="{{ $igv_porc }}" data-validate="{{ route('compras_item.store') }}">

  <div class="form-group col-md-1 no_pr">
    <label>Codigo</label>
    <input class="form-control input-sm {{ $cursor_pointer_producto == "0" ? 'focus' : '' }} " data-validate="positive" data-fieldProducto="ProCodi" data-fieldItem="Detcodi" name="Detcodi" type="text" placeholder="Codigo">
  </div>

  <div class="form-group col-md-3 no_pr">
    <label>Producto</label>
    <div class="input-group">
      <input class="form-control input-sm text-uppercase" data-fieldItem="Detnomb" name="Detnomb" data-fieldProducto="ProNomb" placeholder="Nombre del producto" type="text">
      <span class="input-group-addon" id="boton_buscar" style="cursor: pointer;" data-toggle="tooltip" title="Buscar producto"> <span class="fa fa-search"></span> </span>
    </div>
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Unidad</label>
    <select data-fieldItem="producto.unidades_" name="UniCodi" data-fieldProducto="unidades_" field-text="UniAbre" field-value="Unicodi" class="form-control input-sm keyjump"></select> 
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Stock</label>
    <input data-fieldItem="producto.prosto1" name="Stock" data-fieldProducto="prosto1" disabled="disabled" class="form-control input-sm keyjump">
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Cantidad</label>
    <input class="form-control input-sm keyjump" data-fieldItem="DetCant" name="DetCant" type="text" data-default="1" value="">
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Precio</label>
    <input class="form-control input-sm keyjump" data-fieldItem="DetPrec" name="DetPrec" data-fieldProducto="ProPUVS" data-default="0" type="text">
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Dcto 1</label>
    <input min="0" class="form-control input-sm keyjump" data-fieldItem="DetDct1" name="DetDct1"  data-default="0" type="text">
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Dcto 2</label>
    <input min="0" class="form-control input-sm keyjump" data-fieldItem="DetDct2" name="DetDct2"  data-default="0" type="text">
  </div>

  <div class="form-group col-md-1 no_pr">
    <label>Importe</label>
    <input class="form-control input-sm keyjump"
    readonly="readonly"
    data-fieldProducto="ProPUVS"
    data-fieldItem="DetImpo"
    name="DetImpo"
    data-default="0"
    type="text">          
  </div>
  
  <div class="form-group col-md-1 no_pr">
      <label>&acute;</label>
      <p class="form-control  input-sm">
        <a href="#" class="btn btn-primary btn-fltat btn-block btn-xs" id="add-item"> <span class="fa fa-plus"></span> </a>
      </p>
  </div>

</form>
</div>
  
@endif
