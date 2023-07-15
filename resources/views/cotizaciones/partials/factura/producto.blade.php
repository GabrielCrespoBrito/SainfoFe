@if($create || $modify )

  <div class="row"> 
    <div class="producto_asignacion">
    <input data-namedb="icbper" data-name_item="icbper" name="icbper" type="hidden">
    <div class="form-group col-md-1 no_pr">
      <label>Codigo</label>
      <input class="form-control input-sm inputs_producto" data-namedb="ProCodi" data-name_item="DetCodi" name="producto_codigo" type="text">
    </div>


    <div class="form-group col-md-3 no_pr">
      <label class="label_producto">Producto
      <span class="label-incluye-igv"><label id="incluye_igv"> Incl. IGV <input type="checkbox" value="1" data-name_item="incluye_igv" data-namedb="incluye_igv" name="incluye_igv"> </label></span>

      </label>
      <div class="input-group">
        <input class="form-control input-sm inputs_producto"  data-namedb="ProNomb" data-name_item="DetNomb" name="producto_nombre" placeholder="Nombre del producto" type="text">
        
        <span class="input-group-addon" id="boton_buscar" style="cursor: pointer;"> <span class="fa fa-search"></span> </span>

        <span class="input-group-addon agregar_comentario" data-toggle="tooltip" title="Comentario"> <span class="fa fa-commenting-o"></span></span>


      </div>
    </div>

    <div class="form-group col-md-1 no_pr">
      <label>Unidad</label>
      <select  name="producto_unidad" class="form-control input-sm"></select> 
    </div>

    <div class="form-group padding-xs col-md-1 no_pl">
      <label>Stock</label>
       <input name="producto_stock" data-namedb="prosto1" readonly="readonly" class="form-control input-sm">
    </div>


    <div class="form-group padding-xs col-md-1 no_pl">
      <label>Cantidad</label>
       <input class="form-control input-sm inputs_producto" data-default="1" data-name_item="DetCant" name="producto_cantidad" min="1" data-default="1" type="text" value="1">
    </div>

   <div class="form-group padding-xs col-md-1 no_pl">
      <label>Precio</label>
       <input class="form-control input-sm inputs_producto" data-name_item="DetPrec" name="producto_precio" min="0" data-default="0" value="0" type="text">
    </div>

   <div class="form-group padding-xs col-md-1 no_pl">
      <label>Dcto</label>
       <input min="0" class="form-control input-sm inputs_producto" data-namedb="ProDcto1" data-name_item="DetDcto" name="producto_dct" data-default="0" value="0" type="text">
    </div>


    <div class="form-group padding-xs hidden-xs col-xs-6 col-md-1 no_pl">
      <label>ISC %</label>
       <input min="0" class="form-control input-sm inputs_producto" data-namedb="ISC" data-name_item="DetISP" name="producto_isc" data-default="0" type="text">
    </div>

   <div class="form-group padding-xs col-md-1 no_pl">
      <label>IGV </label>
        <select name="producto_igv" data-namedb="BaseIGV" data-default="GRAVADA" data-name_item="DetBase" class="form-control btn-sm inputs_producto input-sm">
          <option data-porc="1.18" data-value="18" data-default="selected"selected="selected" value="GRAVADA">GRAVADA</option>
          <option data-porc="0" data-value="0" value="INAFECTA">INAFECTA</option>
          <option data-porc="0" data-value="0" value="EXONERADA">EXONERADA</option>
          <option data-porc="0" data-value="0" data-gratuita="1" value="GRATUITA">GRATUITA</option>        
        </select>    
    </div>


    <div class="form-group padding-xs col-md-1 no_pl">  
      <label>Importe</label>    
      <div class="input-group">
        <input class="form-control input-sm inputs_producto" readonly="readonly" data-name_item="DetImpo" name="producto_importe" data-default="0" type="text">
      </div>
    </div>

  <div class="div_comentario" style="display: none">

   <div class="form-group padding-xs col-md-12 col-comentario">
      <label>Comentario</label>
      <textarea class="form-control input-sm inputs_producto" placeholder="" rows="5" name="commentario" data-name_item="DetCome"></textarea>
    </div>

 <div class="form-group padding-xs col-md-3 col-gratuita" style="display: none;">
    <label>Gratuito</label>
     <select name="tipo_gratuito" class="form-control btn-sm">
          <option selected="selected" value="PREMIO">PREMIO</option>
          <option value="DONACIÓN">DONACIÓN</option>
          <option value="RETIRO">RETIRO</option>
          <option value="PUBLICIDAD">PUBLICIDAD</option>
          <option value="BONIFICACIÓN">BONIFICACIÓN</option>
          <option value="ENTREGA TRABAJADORES">ENTREGA TRABAJADORES</option>
        </select>
  </div>


  </div> 




    </div>
    <!-- producto asignación -->

  </div>

@endif