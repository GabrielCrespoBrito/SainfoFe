import { AppModalProducto } from "../productos/modal_producto.js";

window.AppModalProducto = AppModalProducto;

function showModalToAdd(e) {

  e.preventDefault();

  AppModalProducto.draw();
  AppModalProducto.show();
}

function enviarForm(e) {

  e.preventDefault();

  let $form = $("#form-toma");
  let url = $form.attr('action');
  let data = {
    'LocCodi': $form.find('[name=local] option:selected').val(),
    'InvFech': $form.find('[name=InvFech]').val(),
    'InvNomb': $form.find('[name=InvNomb]').val(),
    'InvObse': $form.find('[name=InvObse]').val(),
    'InvEsta': $(this).attr('data-type'),
    'items': [],
  };

  let validate = true;

  $("#items-table tbody tr").each(function(index,dom){

    let $trProduct = $(dom);
    let $input_stock = $trProduct.find('.input-new-stock');
    let stock_value = $input_stock.val().trim();

    if( stock_value == "" || isNaN(stock_value) ){
      notificaciones('El Nuevo Stock tiene que ser un numero' , 'warning');
      $input_stock.focus();       
      return validate = false;
    }

    let info = $trProduct.data('info');

    let info_product = {
      'Id': info.id,
      'ProCodi': info.procodi,
      'proNomb': info.nombre,
      'proMarc': info.marca,
      'UnpCodi': info.unidad,
      'ProStock': info.s_actual,
      'ProInve': $trProduct.find('.input-new-stock').val(),
      'ProPUCS': info.costo,
      'ProPUVS': $trProduct.find('.importe').text(),      
    }
    data.items.push(info_product);
  });

  // Si no paso la validación
  if( validate == false ){
    return false;
  }
  
  if( data.InvEsta == "C" && data.items.length == 0 ){
    notificaciones('Para Finalizar la Toma de Inventario tiene que tener productos cargados, cargue con el Boton Agregar Productos', 'warning');
    return false;
  }

  $("#load_screen").show();

  // Products-Mov
  let funcs = {
    success: function (data) {
      console.log("success", data)
      notificaciones(data.message, 'success');
      location.href = $("#salir").attr('href');
    },
    complete: function () {
      $("#load_screen").hide();
    }
  };

  ajaxs(data, url, funcs)
  return false;
}

function getCodLocal() 
{
  let $local = $("[name=local]");
  if( $local.length ){
    return $local.find('option:selected').attr('data-id');
  }
  else {
    return $('.local-toma').attr('data-id');
  }
}


function agregarProductos()
{
  let $tableBody = $("#items-table tbody");
  let $local = $("[name=local]");

  if (Object.keys(AppModalProducto.elementsSelected).length == 0) {
    $tableBody.empty();
    $local.removeAttr('disabled');
  }

  else {
    $local.attr('disabled', 'disabled');
    // Agregar Productos Nuevos Seleccionados
    let stock = "prosto" + getCodLocal();


    for (const product_code in AppModalProducto.elementsSelected) {
      // console.log(product_code, AppModalProducto.productsDef[product_code]);
      if (!AppModalProducto.productsDef[product_code]) {
        let producto = AppModalProducto.elementsSelected[product_code];
        console.log("marca" , producto );
        // let marcaNombre = producto.marca ? producto.marca.MarNomb : 'SIN DEFINIR';
        let marcaNombre = 'SIN DEFINIR';
        let info = {
          'id': producto.ID,
          'procodi': producto.ProCodi,
          'nombre': producto.ProNomb,
          'marca': marcaNombre,
          'unidad': producto.unpcodi,
          's_actual': producto[stock],
          'costo': producto.ProPUCS,
        }

        let $productoEle =
          $(`<tr data-id="${info.procodi}">
            <td><input type="checkbox"></td>
            <td class="select-input codi">${info.procodi}</td>
            <td class="select-input marca">${info.marca}</td>
            <td class="select-input nombre">${info.nombre}</td>
            <td class="select-input unidad">${info.unidad}</td>
            <td class="stock-actual stock_actual">${info.s_actual}</td>
            <td class="stock-diff">${0 - info.s_actual}</td>
            <td class="codi"><input class="input-new-stock" type="number" min="0" value="0"></td>
            <td class="costo">${info.costo}</td>
            <td class="importe">0.00</td>
          </tr>`);
        $productoEle.attr('data-info', JSON.stringify(info));
        $tableBody.append($productoEle);
      }
    }

    // Quitar Productos No Seleccionados
    for (const product_code in AppModalProducto.productsDef) {
      if (!AppModalProducto.elementsSelected[product_code]) {
        let selector = `tr[data-id=${product_code}]`;
        $tableBody.find(selector).remove();
      }
    }
  }

  AppModalProducto.productsDef = $.extend({}, AppModalProducto.elementsSelected);

  hideShowAcciones();
  notificaciones('Agregados productos exitosamente', 'success');
  AppModalProducto.hide();
}

function cancelarAgregarProductos()
{
  AppModalProducto.elementsSelected =  Object.assign({}, AppModalProducto.productsDef)
}


function hideShowAcciones() {
  let show = $("#items-table tbody [type=checkbox]:checked").length;

  show ? $(".row-actions").removeClass('invisible') : $(".row-actions").addClass('invisible');

  if (!show) {
    $("#input-select-all-def").prop('checked', false)
  }
}


function getInputCheckBoxSelectAll() {
  return $("#input-select-all-def")
}

function showHideButtonsOptions()
{

  this.checked ?
    $("#items-table tbody tr").addClass('selected') :
    $("#items-table tbody tr").removeClass('selected');

  $("#items-table tbody [type=checkbox]").prop('checked', this.checked);

  hideShowAcciones();
}

function InpuCheckboxShowHideButtonsOptions() {
  let $trParent = $(this).parents('tr');

  this.checked ? $trParent.addClass('selected') : $trParent.removeClass('selected')

  hideShowAcciones();
}

function selectUnSelectInput() {
  let $trParent = $(this).parents('tr');

  if ($trParent.is('.selected')) {
    $trParent.removeClass('selected');
    $trParent.find('[type=checkbox]').prop('checked', false);
  }

  else {
    $trParent.addClass('selected');
    $trParent.find('[type=checkbox]').prop('checked', true);
  }

  hideShowAcciones();
}


function calculateFromInput()
{
  calculateNewStock( $(this).parents('tr') );
}

function calculateNewStock($tr_parent)
{
  // let $tr_parent = $input_stock_nuevo.parents('tr');
  let $input_stock_nuevo = $tr_parent.find('.input-new-stock');
  
  let stock_actual_value = Number($tr_parent.find('.stock-actual').text());
  let $td_diff = $tr_parent.find('.stock-diff');
  let coste_value = Number($tr_parent.find('.costo').text());
  let $td_importe = $tr_parent.find('.importe');
  
  
  let importe_nuevo = "0.00";
  let stock_diff = "0.00";

  let new_stock = $input_stock_nuevo.val().trim();


  if (new_stock == "" || isNaN(new_stock)) {
    importe_nuevo = "0.00";
    stock_diff = 0 - stock_actual_value;
  }

  else {
    new_stock = Number(new_stock);    
    importe_nuevo = window.fixedNumber(coste_value * new_stock);
    stock_diff = window.fixedNumber(new_stock - stock_actual_value);
  }

  $td_importe.text( importe_nuevo );
  $td_diff.text( stock_diff );
}


function quitarItemSeleccionados(e)
{
  e.preventDefault();

  let $trProductsSelected = $("#items-table tbody tr.selected");

  if( $trProductsSelected.length ){
    
    $trProductsSelected.each(function(index , dom){
      let $trProduct = $(dom);
      let producto_code = $trProduct.attr('data-id');
      delete AppModalProducto.productsDef[producto_code];
      // console.log("tratando de eliminar", producto_code, AppModalProducto.productsDef );
      $trProduct.remove();
    })
    

    // Actualizar selección en la tabla
    AppModalProducto.elementsSelected = $.extend({}, AppModalProducto.productsDef );

    // console.log("Eliminados",
    //   AppModalProducto.elementsSelected,
    //   AppModalProducto.productsDef
    // )

    hideShowAcciones();
  }

  // Quitar Selección
  let $local = $("[name=local]");

  $("#items-table tbody tr").length ? 
    $local.attr('disabled', 'disabled') :
    $local.removeAttr('disabled');
}

function updateNewStockMassive(e)
{
  e.preventDefault();

  let $input = $(".massive-update-input");
  let value = $(".massive-update-input").val().trim();

  if( value == "" || isNaN(value) ){
    notificaciones('El campo de tiene que ser un numero', 'warning');
    $input.focus();
    return;
  }

  value = Number(value);

  $("#items-table tbody tr.selected").each(function(index,dom){

    let $tr = $(dom);
    $tr.find('.input-new-stock').val(value)
    calculateNewStock($tr)

  });
} 

function setInitModalProduct() {
  
  // console.log("setInitModalProduct");

  let $products = $("#items-table tbody tr");

  let elementsSelected = {};

  if( $products.length ){    
    $products.each(function(index,dom){
      let $tr = $(this);
      let data = JSON.parse($tr.attr('data-info'));
      elementsSelected[data.procodi] = data;
    })

    AppModalProducto.elementsSelected = Object.assign({}, elementsSelected);
    AppModalProducto.productsDef = Object.assign({}, elementsSelected);;
  }
}

function updateStocksProduct(e)
{
  e.preventDefault();

  let $products = $("#items-table tbody tr");

  if( $products.length == 0 ){
    notificaciones('No hay productos seleccionados, agrege los productos con el boton Agregar Productos', 'warning');
    return;
  }

  let local = $("[name=local]").length ? $("[name=local] option:selected").val() : $(".local-toma").attr('data-loccodi');

  let data = {
    'loccodi': local,
    'items': [],
  }

  $products.each(function(index,dom){
    let id = $(dom).attr('data-id');
    data.items.push(id);
  });

  let url = $(this).attr('data-route');

  $("#load_screen").show();

  let funcs = {
    success: function (data) {
      notificaciones('Acción exitosa', 'success', 'Se han actualizado el stock de los productos');
      $("#items-table tbody tr").each(function(index,dom){

        let $tr  = $(dom);
        let info  = JSON.parse($tr.attr('data-info'));
        let s_actual = data.data[$tr.attr('data-id')];
        $tr.find('.stock-actual').text(s_actual);
        info.s_actual = s_actual;
        $tr.attr( 'data-info',  JSON.stringify(info));


      });
    },    
    complete : function(){
      $("#load_screen").hide();
      return;
    }
  }

  ajaxs(data,url,funcs); 
}


Helper.add_events(function () {

  setInitModalProduct();

  $(".enviar-form").on('click', enviarForm);
  $(".add-products").on('click', showModalToAdd);

  AppModalProducto.data.settings.backdrop = true;
  AppModalProducto.init();
  AppModalProducto.add_event_btn(agregarProductos);
  AppModalProducto.add_event_btn_close(cancelarAgregarProductos);
  

  $("#items-table").on('click', '.input-select-all', showHideButtonsOptions);
  $("#eliminate-items").on('click', quitarItemSeleccionados );
  $("#items-table tbody").on('click', '[type=checkbox]', InpuCheckboxShowHideButtonsOptions);
  $("#items-table.active tbody").on('click', 'td.select-input', selectUnSelectInput);
  $("#items-table tbody").on('keyup', '.input-new-stock', calculateFromInput);
  $(".massive-update-stock").on('click', updateNewStockMassive);
  $(".btn-update-stocks").on('click', updateStocksProduct );
  
})

Helper.init();