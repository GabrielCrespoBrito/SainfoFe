<hr>
@if( $guia->canModify() )
<div class="row"> 
  <div class="producto_asignacion">

  <div class="form-group col-md-1 no_pr">
    <label for="exampleInputEmail1">Codigo</label>

    <div class="input-group">
    <input class="form-control input-sm inputs_producto" {{ $estado_edit == 'open_price' ? 'disabled=disabled' : '' }} data-namedb="ProCodi" data-name_item="DetCodi" name="producto_codigo" type="text">
    <span class="input-group-addon hide-in-md btn-search-product-code background-blue-sainfo" id="boton_buscar_code" style="cursor: pointer;"> <span class="fa fa-search"></span> </span>
    </div>

  </div>

  <div class="form-group col-md-5 no_pr">
    <label for="exampleInputEmail1">Producto</label>
    <div class="input-group">
      <input class="form-control input-sm inputs_producto" {{ $estado_edit == 'open_price' ? 'disabled=disabled' : '' }}  data-namedb="ProNomb" data-name_item="DetNomb" name="producto_nombre" placeholder="Nombre del producto" type="text" />
      <span class="input-group-addon" style="padding:0 15px;cursor: pointer;" id="boton_buscar"> <span class="fa fa-search"></span> </span>
      <span class="input-group-addon agregar_comentario"> <span class="fa fa-commenting-o"></span></span>
    </div>
  </div>

  <div class="regular" style="display block">
    <div class="form-group col-md-1 no_pr">
      <label for="exampleInputEmail1">Unidad</label>
      <select {{ $estado_edit == 'open_price' ? 'disabled=disabled' : '' }}  name="producto_unidad" class="form-control input-sm"></select> 
    </div>

    <div class="form-group padding-xs col-md-1 no_pl">
      <label for="exampleInputEmail1">Stock</label>
       <input name="producto_stock" data-namedb="prosto1" readonly="readonly" class="form-control input-sm">
    </div>

    <div class="form-group padding-xs col-md-1 no_pl">
      <label for="exampleInputEmail1">Cantidad</label>
       <input class="form-control input-sm inputs_producto" {{ $estado_edit == 'open_price' ? 'disabled=disabled' : '' }} data-default="1" data-name_item="DetCant" name="producto_cantidad" min="1" data-default="1" type="text" value="1">
    </div>

   <div class="form-group padding-xs col-md-1 no_pl">
      <label for="exampleInputEmail1">Precio</label>
       <input class="form-control input-sm inputs_producto" data-name_item="DetPrec" name="producto_precio" min="0" data-default="0" type="text">
    </div>


   <div class="form-group padding-xs col-md-2 no_pl">
      <label for="exampleInputEmail1">Importe</label>
        <input class="form-control input-sm inputs_producto" readonly="readonly" data-name_item="DetImpo" name="producto_importe" data-default="0" type="text">          
    </div>
  </div>

<div class="div_comentario" style="display: none">
 <div class="form-group padding-xs col-md-12 ">
    <label>Comentario</label>
      <input class="form-control input-sm inputs_producto" placeholder="Redacta lo que quieras" rows="5" name="commentario" data-name_item="DetCome">
  </div>
</div> 

  </div>
  <!-- /2do piso producto inputs -->

  </div>
  <!-- producot_asginacion -->
</div>
@endif