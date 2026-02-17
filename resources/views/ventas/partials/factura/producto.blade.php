<div class="row">
    <div class="producto_asignacion">

        <div class="form-group col-md-1 no_pr">
            <input data-namedb="icbper" data-name_item="icbper" name="icbper" type="hidden">
            <label>Codigo</label>
            <div class="input-group">
                <input class="form-control input-sm inputs_producto" data-namedb="ProCodi" data-name_item="DetCodi"
                    name="producto_codigo" type="text">

                <span class="input-group-addon hide-in-md btn-search-product-code background-blue-sainfo"
                    id="boton_buscar_code" style="cursor: pointer;"> <span class="fa fa-search"></span> </span>

            </div>
        </div>

        <div class="form-group col-md-3 no_pr">

            <label class="label_producto">Producto
                <span class="label-incluye-igv"><label id="incluye_igv"> Incl. IGV <input type="checkbox" value="1"
                            data-name_item="incluye_igv" data-namedb="incluye_igv" name="incluye_igv"> </label></span>
            </label>

            <div class="input-group">
                <input class="form-control input-sm inputs_producto" data-namedb="ProNomb" data-name_item="DetNomb"
                    name="producto_nombre" placeholder="Nombre del producto" type="text">

                <span class="input-group-addon btn-search-product-nombre background-blue-sainfo" id="boton_buscar"
                    style="cursor: pointer;" data-toggle="tooltip" title="Buscar producto"> <span
                        class="fa fa-search"></span> </span>

                <span class="input-group-addon agregar_comentario" data-toggle="tooltip" title="Comentario"> <span
                        class="fa fa-commenting-o"></span></span>

            </div>


        </div>

        <div class="regular" style="display block">
            <div class="form-group col-xs-6  col-md-1 no_pr">
                <label>Unidad <span title="Calcular Peso" class="calculate-peso hide"> <span
                            class="fa fa-calculator"></span><span> </label>
                <select name="producto_unidad" class="form-control input-sm"></select>
            </div>

            <div class="form-group col-xs-6 padding-xs col-md-1 no_pl">
                <label>Stock</label>
                {{-- inputs_producto --}}
                <input name="producto_stock" data-namedb="prosto1" data-name_item="prosto1" readonly="readonly"
                    class="form-control input-sm inputs_producto">
            </div>

            <div class="form-group padding-xs col-xs-6 col-md-1 no_pl">
                <label>Cantidad</label>
                <input class="form-control input-sm inputs_producto" data-default="1" data-name_item="DetCant"
                    name="producto_cantidad" min="1" data-default="1" type="text" value="1">
            </div>

            <div class="form-group padding-xs col-xs-6 col-md-1 no_pl">
                <label>Precio
                    <abbr class="lpm labelPrecioMinimo" title="Precio Mínimo"></abbr>
                </label>
                <input class="form-control input-sm inputs_producto" data-name_item="DetPrec" name="producto_precio"
                    min="0" data-default="0" type="text">
            </div>

            <div class="form-group padding-xs hidden-xs col-xs-6 col-md-1 no_pl">
                <label>Dcto %</label>
                <input min="0" class="form-control input-sm inputs_producto" data-namedb="ProDcto1"
                    data-name_item="DetDcto" name="producto_dct" data-default="0" type="text">
            </div>

            <div class="form-group padding-xs hidden-xs col-xs-6 col-md-1 no_pl">
                <label>ISC %</label>
                <input min="0" class="form-control input-sm inputs_producto " data-namedb="ISC"
                    data-name_item="DetISP" name="producto_isc" data-default="0" type="text">
            </div>

            <div class="form-group padding-xs col-xs-6 col-md-1 no_pl">
                <label>IGV </label>
                <select name="producto_igv" data-namedb="BaseIGV" data-default="GRAVADA" data-name_item="DetBase"
                    class="form-control btn-sm inputs_producto input-sm">
                    <option data-porc="1.18" data-value="18" data-default="selected" selected="selected"
                        value="GRAVADA">GRAVADA</option>
                    <option data-porc="0" data-value="0" value="INAFECTA">INAFECTA</option>
                    <option data-porc="0" data-value="0" value="EXONERADA">EXONERADA</option>
                    <option data-porc="0" data-value="0" data-gratuita="1" value="GRATUITA">GRATUITA</option>
                </select>
            </div>

            <div class="form-group padding-xs col-xs-6 col-md-1 no_pl">
                <label>Importe</label>
                <input class="form-control input-sm inputs_producto" readonly="readonly" data-name_item="DetImpo"
                    name="producto_importe" data-default="0" type="text">
            </div>

            <div style="margin-top:10px" class="form-group padding-xs col-xs-12 no_pl">
                <a href="#"
                    class="btn btn-block pull-right btn-xs hidden-md hidden-lg col-xs-12 col-sm-12 btn-primary item-accion crear">
                    <span class="fa fa-plus"></span> Agregar producto</a>
            </div>

        </div>

        <div class="div_comentario" style="display: none">
            <div class="col-comentario col-lg-12 col-md-12 col-xs-12 col-sm-12">

                <label>Comentario</label>
                <textarea class="form-control input-sm inputs_producto" placeholder="" rows="5" name="commentario"
                    data-name_item="DetCome"></textarea>
            </div>

            <div class="form-group padding-xs col-md-3 col-gratuita" style="display: none;">
                <label>Gratuito</label>
                <select name="tipo_gratuito" data-name_item="TipoIGV" class="form-control btn-sm">
                    @foreach ($tipos_igvs as $tipo)
                        <option value="{{ $tipo->cod_sunat }}">{{ $tipo->descripcion }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>
    <!-- /2do piso producto inputs -->

</div>
<!-- producot_asginacion -->

</div>
