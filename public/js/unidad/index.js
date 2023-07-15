H = Helper__;
window.tr_ajax = null;
const getTrData = ($tr) => {
  return {
    'id': $tr.data().info.Id,
    'Unicodi': $tr.data().info.Unicodi,
    'UniPUCD': $tr.find("[name=costoDolar]").val(),
    'UniPUCS': $tr.find("[name=costoSol]").val(),
    'UniMarg': $tr.find("[name=margen]").val(),
    'UNIPUVS': $tr.find("[name=precioVentaSol]").val(),
    'UNIPUVD': $tr.find("[name=precioVentaDolar]").val(),    
    'UNIPMVS': $tr.find("[name=precioMinVentaSoles]").val(),
    'UNIPMVD': $tr.find("[name=precioMinVentaDolar]").val(),    
  };
}

function save($tr) {
  if (!validateInputs($tr)) {
    return;
  }

  window.tr_ajax = $tr;

  let id = $tr.data().info.Unicodi;
  let data = getTrData($tr);

  let url = url_save.replace("@@", id);

  $("#load_screen").show();

  let funcs = {
    success: (data) => {
      console.log("tr_actualizado", data, tr_ajax );
      notificaciones("Informaciòn actualizada satisfactoriamente", "success");
      window.tr_ajax.addClass('success');
      updateDefaultValues( window.tr_ajax , data.data );
      actionPrecioEdit(true, window.tr_ajax);
      showHideMassiveOptions(true);
    },
    error: errorSave,
    complete: function () {
      $("#load_screen").hide();
    }
  };

  ajaxs(data, url, funcs);
}

function saveMassive() {

  let $trs = $("#datatable tbody tr[data-edit=1]");

  let validate = true;
  let data = [];

  $trs.each((index, tr) => {
    const $tr = $(tr);
    if (!validateInputs($tr)) {
      validate = false;
      console.log($tr);
      return false;
    }
    data.push(getTrData($tr));
  });

  if( ! validate ){
    notificaciones('Corregir los datos suministrados', 'error');
    return;
  }

  
  if (validate) {
    
    $("#load_screen").show();

    const $datatable = $("#datatable");
    const url = $datatable.attr('data-update_massive');

    let funcs = {
      success: (res) => {
        notificaciones(res.message, 'success');
        let $this = $datatable.find('thead .update-precios')         
        updateMassiveDefaultValues();                
        actionPrecioEdit(true, $this.parents('tr'))
        actionMassive(true, $this);
      },
      error: errorSave,
      complete: function (res) {
        console.log("complete", res)
        $("#load_screen").hide();
      }
    };

    ajaxs({ data }, url, funcs);
  }
}



function errorSave(data) {
  console.log("data error", data);
  if (data.responseJSON) {
    if (data.responseJSON.message) {
      notificaciones(data.responseJSON.message, "error");
      if (data.responseJSON.errors) {
        const errors = data.responseJSON.errors;
        for( prop in errors ){
          let errs = errors[prop];
          console.log({ errs });
          errs.forEach( (error) => notificaciones(error, 'error') );
        }
      }
    }
  }
  else {
    notificaciones("No se pudo guardar la información", "error");
  }
}


function validateInitial($tr) {

  let valid = true;
  // 
  $tr.find('input').each(function (index, dom) {    
    if (isNaN(this.value) || this.value == "" || this.value.toLowerCase() == "infinity") {
      $(this).addClass('has-error')
      valid = false;
    }
    else {
      $(this).removeClass('has-error')
    }
  })

  return valid;
}


function validateInputs($tr) {
    
  let valid = true;

  if (!validateInitial($tr)) {
    return;
  }

  if (valid) {

    let margen = Number($("[name=margen]", $tr).val());
    let precioVentaSol = Number($("[name=precioVentaSol]", $tr).val());
    let precioVentaDolar = Number($("[name=precioVentaDolar]", $tr).val());
    let precioMinVentaSol = Number($("[name=precioMinVentaSol]", $tr).val());
    let precioMinVentaDolar = Number($("[name=precioMinVentaDolar]", $tr).val());
    let costoSol = Number($("[name=costoSol]", $tr).val());
    let costoDolar = Number($("[name=costoDolar]", $tr).val());
    // costoSol
    // 
    if (costoDolar > precioVentaDolar) {
      // console.log("Aqui");
      $("[name=costoSol]", $tr).addClass('has-error');
      notificaciones('El costo en dolares no puede ser mayor al precio de venta en dolares', 'error');
      return false;
    }

    if( isNaN(margen)  ){
      $("[name=margen]", $tr).addClass('has-error');
      notificaciones('El Margen Debe ser un numero', 'error');
      return false;
    }


    if (costoSol > precioVentaSol) {
      notificaciones('El costo en soles no puede ser mayor al precio de venta en soles', 'error');
      return false;
    }

  }

  return valid;
}

function setValueInput(input, value, nameInput)
{
  const inputDecimals = {
    // costoDolar : decimal_dolares,
    // costoSol : decimal_soles,
    costoDolar : 2,
    costoSol : 2,
    margen : 2 ,
    precioVentaSol : decimal_soles ,
    precioVentaDolar : decimal_dolares ,
    precioMinVentaSoles : decimal_soles ,
    precioMinVentaDolar : decimal_dolares ,
  }

  let realNameInput = input.attr('name');

  // ------------------------------------------------------------
  // ------------------------------------------------------------
  // |----------------------------------------------------------|
  // |console.log("DecimalBy", realNameInput, decimal );        |
  // |if (!input.is("[name=" + realNameInput + "]")) {          |
  // |----------------------------------------------------------|
  // ------------------------------------------------------------
  // ------------------------------------------------------------

  if (input.is(`[name="${nameInput}"]`) == false ) {
    let decimal = inputDecimals[realNameInput];
    input.val(fixValue(value, decimal));
  }
}


function makeCalculate(e) {

  console.log("calculo");
  let
    $col = $(this),
    $tr = $col.parents('tr'),
    $input = $tr.find('input'),
    name = $col.attr('name');

  if ($input.is('[readonly]')) {
    return;
  }

  let keyCode = e.keyCode;

  if (
    keyCode == 9 ||
    keyCode == 37 ||
    keyCode == 38 ||
    keyCode == 39 ||
    keyCode == 40) {
    return;
  }


  let valid = validateInitial($tr)

  if (valid) {

    let margen = Number($("[name=margen]", $tr).val());
    let precioVentaSol = Number($("[name=precioVentaSol]", $tr).val());
    let precioVentaDolar = Number($("[name=precioVentaDolar]", $tr).val());
    let precioMinVentaSoles = Number($("[name=precioMinVentaSoles]", $tr).val());
    let precioMinVentaDolar = Number($("[name=precioMinVentaDolar]", $tr).val());    
    let costoSol = Number($("[name=costoSol]", $tr).val());
    let costoDolar = Number($("[name=costoDolar]", $tr).val());
 
    $(".jq-toast-wrap").remove()

    AppCalculate.setValues(costoSol, costoDolar, margen, precioVentaSol, precioVentaDolar, precioMinVentaSoles, precioMinVentaDolar);

    if (name == "margen") {
      AppCalculate.calculatePrecioVenta();
    }
    else if (name == "precioMinVentaDolar") {
      AppCalculate.calculatePrecioMinVenta(false);
    }
    else if (name == "precioMinVentaSoles") {
      AppCalculate.calculatePrecioMinVenta(true);
    }    
    else if (name == "costoSol") {
      AppCalculate.calculateCosto(true);
      AppCalculate.calculatePrecioVenta();
    }
    else if (name == "costoDolar") {
      AppCalculate.calculateCosto(false);
      AppCalculate.calculatePrecioVenta();
    }

    else if (name == "precioVentaSol") {
      AppCalculate.calculateMargen(true);
    }
    else if (name == "precioVentaDolar") {
      AppCalculate.calculateMargen(false);
    }

    // Poner datas
    if (name == "margen") {
      AppCalculate.calculatePrecioVenta();
    }
    else if (name == "costoSol") {
      AppCalculate.calculateCosto(true);
      AppCalculate.calculatePrecioVenta();
    }
    else if (name == "costoDolar") {
      AppCalculate.calculateCosto(false);
      AppCalculate.calculatePrecioVenta();
    }

    else if (name == "precioVentaSol") {
      AppCalculate.calculateMargen(true);
    }
    else if (name == "precioVentaDolar") {
      AppCalculate.calculateMargen(false);
    }

    // setValueInput($("[name=costoSol]", $tr), AppCalculate.getCostoSol(), name);
    // setValueInput($("[name=costoDolar]", $tr), AppCalculate.getCostoDolar(), name);
    // setValueInput($("[name=margen]", $tr), AppCalculate.getMargen(), name);
    // setValueInput($("[name=precioVentaSol]", $tr), AppCalculate.getPrecioVentaSol(), name);
    // setValueInput($("[name=precioVentaDolar]", $tr), AppCalculate.getPrecioVentaDolar(), name);
    // setValueInput($("[name=precioMinVentaSoles]", $tr), AppCalculate.getPrecioMinVentaSol(), name);
    // setValueInput($("[name=precioMinVentaDolar]", $tr), AppCalculate.getPrecioMinVentaDolar(), name);

    setValueInput($("[name=costoSol]", $tr), AppCalculate.getCostoSol(), name);
    setValueInput($("[name=costoDolar]", $tr), AppCalculate.getCostoDolar(), name);
    setValueInput($("[name=margen]", $tr), AppCalculate.getMargen(), name);
    setValueInput($("[name=precioVentaSol]", $tr), AppCalculate.getPrecioVentaSol(), name);
    setValueInput($("[name=precioVentaDolar]", $tr), AppCalculate.getPrecioVentaDolar(), name);
    setValueInput($("[name=precioMinVentaSoles]", $tr), AppCalculate.getPrecioMinVentaSol(), name);
    setValueInput($("[name=precioMinVentaDolar]", $tr), AppCalculate.getPrecioMinVentaDolar(), name);

  }

  return false;
}

let AppCalculate = {

  tipoCambio: null,

  info: {
    costoSol: 0,
    costoDolar: 0,
    margen: 0,
    precioVentaSol: 0,
    precioVentaDolar: 0,
    precioMinVentaSol: 0,
    precioMinVentaDolar: 0,
  },

  setValues: function (costoSol, costoDolar, margen, precioVentaSol, precioVentaDolar, precioMinVentaSoles, precioMinVentaDolar) {
    this.setCostoSol(costoSol);
    this.setCostoDolar(costoDolar);
    this.setMargen(margen);
    this.setPrecioVentaSol(precioVentaSol);
    this.setPrecioVentaDolar(precioVentaDolar);
    this.setPrecioMinVentaSol(precioMinVentaSoles);
    this.setPrecioMinVentaDolar(precioMinVentaDolar);    
  },

  multipledByTipoCambio: function (value) {
    return value * this.getTipoCambio();
  },

  dividedByTipoCambio: function (value) {
    return value / this.getTipoCambio();
  },
  // calculateMargen

  calculateCosto: function (sol = true) {

    if (sol) {
      let costoDolar = this.dividedByTipoCambio(this.getCostoSol());
      this.setCostoDolar(costoDolar);
    }
    else {
      let costoSol = this.multipledByTipoCambio(this.getCostoDolar());
      this.setCostoSol(costoSol);
    }
  },

  calculatePrecioVenta: function () {

    let precioVentaSol = this.getCostoSol() + ((this.getCostoSol() / 100) * this.getMargen());
    let precioVentaDolar = this.getCostoDolar() + ((this.getCostoDolar() / 100) * this.getMargen());

    this.setPrecioVentaSol(precioVentaSol);
    this.setPrecioVentaDolar(precioVentaDolar);
  },


  calculatePrecioMinVenta: function (sol) {

    if (sol) {
      let precioMinVentaDolar = this.dividedByTipoCambio(this.getPrecioMinVentaSol());
      this.setPrecioMinVentaDolar(precioMinVentaDolar);
    }
    else {
      let precioMinVentaSol = this.multipledByTipoCambio(this.getPrecioMinVentaDolar());
      this.setPrecioMinVentaSol(precioMinVentaSol);
    }
  },

  calculateMargen: function (sol = true) {

    let margen = 0;

    if (sol) {
      margen = ((this.getPrecioVentaSol() / this.getCostoSol()) - 1) * 100;
      let precioVentaDolar = this.dividedByTipoCambio(this.getPrecioVentaSol());
      this.setPrecioVentaDolar(precioVentaDolar);
    }
    else {
      margen = ((this.getPrecioVentaDolar() / this.getCostoDolar()) - 1) * 100;
      let precioVentaSol = this.multipledByTipoCambio(this.getPrecioVentaDolar());
      this.setPrecioVentaSol(precioVentaSol);
    }

    this.setMargen(margen);
  },


  // Setters

  setTipoCambio: function (value) {
    this.tipoCambio = Number(value);
  },
  setCostoSol: function (value) {
    this.info.costoSol = value;
  },
  setCostoDolar: function (value) {
    this.info.costoDolar = value;
  },
  setMargen: function (value) {
    this.info.margen = value;
  },
  setPrecioVentaSol: function (value) {
    this.info.precioVentaSol = value;
  },
  setPrecioVentaDolar: function (value) {
    this.info.precioVentaDolar = value;
  },

  setPrecioMinVentaSol: function (value) {
    this.info.precioMinVentaSol = value;
  },
  setPrecioMinVentaDolar: function (value) {
    this.info.precioMinVentaDolar = value;
  },

  // Getters
  getTipoCambio: function () {
    return this.tipoCambio;
  },
  getCostoSol: function () {
    return this.info.costoSol;
  },
  getCostoDolar: function () {
    return this.info.costoDolar;
  },
  getMargen: function () {
    return this.info.margen;
  },
  getPrecioVentaSol: function () {
    return this.info.precioVentaSol;
  },
  getPrecioVentaDolar: function () {
    return this.info.precioVentaDolar;
  },
  getPrecioMinVentaSol: function () {
    return this.info.precioMinVentaSol;
  },
  getPrecioMinVentaDolar: function () {
    return this.info.precioMinVentaDolar;
  },  

};


function updateDefaultValues($tr , data)
{
  console.log("data",data);

  $tr.data('info' , data);

  $tr.find('[name=costoDolar]')
  .val(data.UniPUCD)
  .attr('data-default',  data.UniPUCD)

  $tr.find('[name=costoSol]')
  .val(data.UniPUCS)
  .attr('data-default',  data.UniPUCS);

  $tr.find('[name=margen]')
  .val(data.UniMarg)
  .attr('data-default',  data.UniMarg)

  $tr.find('[name=precioVentaSol]')
  .val(data.UNIPUVS)
  .attr('data-default',  data.UNIPUVS)

  $tr.find('[name=precioVentaDolar]')
  .val(data.UNIPUVD)
  .attr('data-default', data.UNIPUVD);

  $tr.find('[name=precioMinVentaSoles]')
    .val(data.UNIPMVS)
    .attr('data-default', data.UNIPMVS)

  $tr.find('[name=precioMinVentaDolar]')
    .val(data.UNIPMVD)
    .attr('data-default', data.UNIPMVD);

}


function updateMassiveDefaultValues() {

  $("#datatable tbody tr[data-edit=1]").each((index,dom) => {
    
    const $tr = $(dom);    
  
    let $costoDolar = $tr.find('[name=costoDolar]')
      $costoDolar.attr('data-default', $costoDolar.val())
  
    let $costoSol = $tr.find('[name=costoSol]')
      $costoSol.attr('data-default', $costoSol.val())
  
    let $margen = $tr.find('[name=margen]')
      $margen.attr('data-default', $margen.val())
  
    let $precioVentaSol = $tr.find('[name=precioVentaSol]')
      $precioVentaSol.attr('data-default', $precioVentaSol.val())

    let $precioVentaDolar = $tr.find('[name=precioVentaDolar]')
      $precioVentaDolar.attr('data-default', $precioVentaDolar.val())

    let $precioMinVentaSoles = $tr.find('[name=precioMinVentaSoles]')
    $precioMinVentaSoles.attr('data-default', $precioMinVentaSoles.val())

    let $precioMinVentaDolar = $tr.find('[name=precioMinVentaDolar]')
    $precioMinVentaDolar.attr('data-default', $precioMinVentaDolar.val())

  })
  
}


function appendInput(value, type, row, meta) {

  let names =
  {
    5: { name : 'costoDolar' , decimales : 2 } ,
    6: { name : 'costoSol' , decimales : 2 } ,
    7: { name : 'margen' , decimales : 0 } ,
    8: { name : 'precioVentaSol' , decimales : decimal_soles } ,
    9: { name : 'precioVentaDolar' , decimales : decimal_dolares } ,
    10: { name :'precioMinVentaSoles' , decimales : decimal_soles } ,
    11: { name :'precioMinVentaDolar' , decimales : decimal_dolares } ,
  }

  if (meta.row === 0) {
  }

  value = fixValue(value, names[meta.col].decimales);

  return `
  <input 
  readonly=readonly
  class="form-control input-table" name="${names[meta.col].name}" data-decimal="${names[meta.col].decimales}"  type="text" min="0" data-default="${value}" value="${value}">
  `
}

function editPrecio(e) {

  e.preventDefault();
  let $this = $(this);

  actionPrecioEdit(false, $this.parents('tr'))
  actionMassive(false, $this);

}

const actionMassive = (action, $td) => {

  let isThead = $td.parents('thead').length

  if ( isThead ) {
    $("#datatable tbody tr").each((index, dom) => {
      actionPrecioEdit(action, $(dom));
    })
    return;
  }

  showHideMassiveOptions(action);
}

const showHideMassiveOptions = (action) => {
  
  const $trActionHeader = $("#datatable thead tr");

  if (action) {
    if ($trActionHeader.is('[data-edit=1]')) {

      if (!$("#datatable tbody tr[data-edit=1]").length) {
        actionPrecioEdit(true, $trActionHeader);
      }
    }
  }

  else {
    if ($("#datatable tbody tr[data-edit=0]").length) {
      actionPrecioEdit(false, $trActionHeader);
    }
  }
}



function cancelEditPrecio(e) {
  e.preventDefault();
  actionPrecioEdit(true, $(this).parents('tr'))
  actionMassive(true, $(this));

}

function editTipoCambio(e) {
  e.preventDefault();
  actionTCEdit(false)
}

function cancelEditTipoCambio(e) {
  e.preventDefault();
  actionTCEdit(true)
}



function actionTCEdit(default_action = true) {
  let $edit_btn = $(".action-default");
  let $update_btns = $(".action-edit");
  let $tc = $("[name=tipo_cambio]");

  if (default_action) {

    $tc
      .attr('readonly', 'readonly')
      .val($tc.attr('data-default'))
    $edit_btn.show();
    $update_btns.hide();
  }

  else {
    $tc
      .removeAttr('readonly')
      .val($tc.attr('data-default'))
    $edit_btn.hide();
    $update_btns.show();
  }

}

function actionPrecioEdit( default_action = true, $tr, updateData = false) {

  let $edit_btn = $tr.find(".action-precio-default");
  let $update_btns = $tr.find(".action-precio-edit");
  let $inputs = $tr.find("input");

  $tr.removeClass('success');

  if (default_action) {
    $edit_btn.show();
    $update_btns.hide();
  }
  else {
    $edit_btn.hide();
    $update_btns.show();
  }

  $inputs.each(function (index, dom) {
    let $input = $(this);

    $input.removeClass('has-error');

    if (default_action) {
      $input
        .attr('readonly', 'readonly')
        .val($input.attr('data-default'))
    }
    else {
      $input
        .removeAttr('readonly')
        .val($input.attr('data-default'))
    }
  })

  $tr.attr('data-edit', Number(!default_action));
}

function updatePrecio(e) {
  e.preventDefault();
  const $td = $(e.target);

  if ($td.parents('thead').length) {
    saveMassive();
  }

  else {
    save($(this).parents('tr'));
  }
}

function updateTipoCambio(e) {
  e.preventDefault();

  $("#load_screen").show();
  let url = $(this).data('url');
  let data = { 'tipo_cambio': $("[name=tipo_cambio]").val() };
  let funcs = {
    'complete': function () {
      $("#load_screen").hide();
      actionTCEdit(true)
      updateTipoCambioParaCalculos();
      window.table.draw();
    },
    success: (data) => {
      notificaciones(data.message, 'success');
      $("[name=tipo_cambio]").attr('data-default', data.tipo_cambio)
    }
  }
  ajaxs(data, url, funcs);
}


function getRoute(id) {
  window.routeReport = window.routeReport || $("#datatable").attr('data-route');
  return window.routeReport.replace('xxx', id);
}

function addRouteToReporte(value, type, row, meta) {
  let route = getRoute(value);
  return `<a href="${route}" target="_blank" class="btn btn-xs btn-default btn-flat" title="Movimientos Compra/Venta"> <span class="fa fa-exchange"><span> </a>`
}

function initDatable()
{
  const searchInput = $('#datatable').attr('data-id');
  let table = $('#datatable')
  window.table = table.DataTable({
    "pageLength": 10,
    'search': {
      'search': searchInput
    },
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": table.attr('data-url'),
      "data": function (d) {

        const local_id = $("[name=local_id]").val() || $(".informacion_empresa-local").find('option:selected').attr('data-id');
        return $.extend({}, d, {
          "local_id": local_id,
          "grupo_id": $("[name=grupo_id]").val(),
          "familia_id": $("[name=familia_id]").val(),
          "marca_id": $("[name=marca_id]").val(),
          "LisCodi": $("[name=LisCodi]").val(),
        });
      }
    },
    createdRow: function (row, data, index) {
      const $row = $(row);
      $row.data('info', data);
      $row.attr('data-edit', '0');
    },
    "columns": [
      { data: 'ProCodi', orderable: false, searchable: false },
      { data: 'UniAbre', orderable: false, searchable: false },
      { data: 'producto_link', orderable: false, searchable: false },
      { data: 'ProUltC', orderable: false, searchable: false },
      { data: 'moneda', orderable: false, searchable: false },
      { data: 'UniPUCD', render: appendInput, orderable: false, searchable: false },
      { data: 'UniPUCS', render: appendInput, orderable: false, searchable: false },
      { data: 'UniMarg', render: appendInput, orderable: false, searchable: false },
      { data: 'UNIPUVS', render: appendInput, orderable: false, searchable: false },
      { data: 'UNIPUVD', render: appendInput, orderable: false, searchable: false },
      { data: 'UNIPMVS', render: appendInput, orderable: false, searchable: false },
      { data: 'UNIPMVD', render: appendInput, orderable: false, searchable: false },      
      { data: 'moneda', render: appendBtns,   orderable: false, searchable: false, },
      { data: 'ProCodi', render: addRouteToReporte, orderable: false, searchable: false },
    ]
  });
}

function appendBtns() {
  return `
    <a href="#" title="Modifica" class="btn action-precio-default edit-precios btn-xs btn-default"> <span class="fa fa-pencil"></span> </a>

    <a href="#" style="visibility:hidden" class="btn action-precio-default btn-xs btn-default"> <span class="fa fa-pencil"></span> </a>

    <a href="#" style="display:none" title="El tipo de cambio nuevo, se utilizara para recalcular los productos en dolares" data-url="{{ route('unidad.update_tc') }}" class="btn action-precio-edit update-precios btn-xs btn-primary"> <span class="fa fa-save"></span> </a>

    <a href="#" style="display:none" title="Cancelar la modificaciòn" class="btn action-precio-edit cancel-edit-precios btn-xs btn-default"> <span class="fa fa-close"></span> </a>
  `;
}

function updateTipoCambioParaCalculos()
{
  AppCalculate.setTipoCambio($("[name=tipo_cambio]").attr('data-default'));
}

function showDivFilter() {
  let $btn = $(".show-div-filters");
  let url = $btn.data('url');

  ajaxs({}, url, {
    'success': (html) => {
      // console.log("ajaxs", html)
      $("#div-filters").html(html);
      showHideDivs($btn);
    }
  });
}

function showDivs() {
  let $btn = $(this)

  if ($btn.is('.show-div-filters')) {
    if (!$btn.is('.active') && !$("#div-filters").find('.contenido').length) {
      showDivFilter();
      return;
    }
  }

  showHideDivs($btn);
}


function showHideDivs($btn) {

  let is_active = $btn.is('.active');

  let target = $btn.data('target');
  let $div = $(target);

  $div.toggle(!is_active)

  $btn.toggleClass('active');
}

function agregarASelect(data, select, name_codi, name_text) {

  console.log("agregarASelect", data)

  let select_agregar = $("[name=" + select + "]");
  select_agregar.empty();

  for (let i = 0; i < data.length; i++) {
    let actual_data = data[i];

    let option = $("<option></option>")
      .attr('value', actual_data[name_codi])
      .text(actual_data[name_text]);

    select_agregar.append(option);
  }
  window.table.draw();
}

function agregar_select_familias(familias) {

  if (familias.length) {
    familias.unshift({
      "famCodi": '',
      "famNomb": '-- TODOS --'
    });
    agregarASelect(familias, "familia_id", "famCodi", "famNomb");
    $("[name=grupo_id] option:selected").attr('data-familias', JSON.stringify(familias));
  }
  else {
    agregarASelect([{ famCodi: '', famNomb: '-- Sin familias  --' }], "famiia_id", "famCodi", "famNomb");
  }

}

function updateFamilia() {
  let grupo = $("[name=grupo_id] option:selected");

  console.log('grupo.val()', grupo.val());

  if (!grupo.val()) {
    agregarASelect([{ famCodi: '', famNomb: '-- TODOS  --' }], "familia_id", "famCodi", "famNomb");
    return;
  }

  let id_grupo = grupo.val();

  if (grupo.attr('data-familias')) {
    let familias = grupo.data('familias');
    agregarASelect(familias, "familia_id", "famCodi", "famNomb");
  }
  else {
    let url = $("[name=grupo_id]").data('url');
    let data = { id_grupo: id_grupo }
    let funcs = { success: agregar_select_familias };
    ajaxs(data, url, funcs);
  }
}

//

function chooseOpcion() {
  let $thisBtn = $(this);
  let $brotherBtn = $thisBtn.siblings();

  $thisBtn.toggleClass('active');
  $brotherBtn.removeClass('active');


  $thisBtn.parents('.btn-group').removeClass('has-error');
}



function updateMasive()
{

  $('.btn-group-tipo').removeClass('has-error');
  $('.btn-group-campo').removeClass('has-error');
  $('.input-group-value').removeClass('has-error');

  if (!$("#div-filters .contenido").length) {
    notificaciones('Tiene que filtrar primero los productos que quiere actualizar, por favor presionar el boton de "Filtros"');
    return;
  }

  if (!$("#div-filters .contenido [name=grupo_id] option:selected").val()) {
    notificaciones('Tiene que al menos filtrar por grupo: no se puede aplicar una actualizaciòn a todos los registros')
    return;
  }

  if (!$(".accion-elegir-tipo.active").length) {
    notificaciones('Tiene que elegir Agregar o Disminuir')
    $('.btn-group-tipo').addClass('has-error');
    return;
  }

  if (!$(".accion-elegir-campo.active").length) {
    notificaciones('Tiene que elegir Costo o Margen Gan.')
    $('.btn-group-campo').addClass('has-error');
    return;
  }

  let $input = $("[name=valor]")
  let input_value = $.trim($input.val());

  if (
    isNaN(input_value) || input_value == "") {
    notificaciones('Tiene que poner un valor numerico en el campo del porcentaje ', 'error')
    $('.input-group-value').addClass('has-error');
    return;
  }

  $("#load_screen").show();

  let data = {
    'tipo': $(".accion-elegir-tipo.active").attr('data-value'),
    'campo': $(".accion-elegir-campo.active").attr('data-value'),
    'value': input_value,
    'local_id': $("[name=local_id]").val(),
    'lista_id': $("[name=LisCodi]").val(),
    'grupo_id': $("[name=grupo_id]").val(),
    'familia_id': $("[name=familia_id]").val(),
    'marca_id': $("[name=marca_id]").val(),
  };

  let url = $(this).attr('data-url');

  let funcs = {
    success: (data) => {
      notificaciones(data.message, 'success');
      $(".accion-btn").removeClass('active');
      $input.val("")
    },
    complete: () => {
      $("#load_screen").hide();
      window.table.draw();
    }
  }

  ajaxs(data, url, funcs);
}

const setBtnsToMassiveModify = () => {

  const $table = $("#datatable");
  let $tdMassive = $table.find('thead tr td:eq(12)');

  $tdMassive.empty();

  // console.log(  'tdMassive', $tdMassive );
  $tdMassive.append(appendBtns);
}

function changeLocal(e) {
  const listas = JSON.parse($(e.target).find('option:selected').attr('data-listas') );
  $("[name=LisCodi]").empty();

  const $listas = listas.map((lista) => {
    return `<option value="${lista.LisCodi}"> ${lista.LisNomb}</option>`
  })

  $listas.unshift(`<option value=""> - TODOS - </option>`
  )

  $("[name=LisCodi]").append($listas);

  window.table.draw();
}


function setDecimals()
{
  let $table = $("#datatable");

  window.decimal_soles = $table.attr('data-dsoles');
  window.decimal_dolares = $table.attr('data-ddolares');
}


H.add_events(function () {

  setDecimals();

  setTimeout(() => {
    initDatable();
  }, 1000);

  $("#div-filters").on('change', "[name=LisCodi],[name=familia_id],[name=marca_id]", function () { window.table.draw() })
  $("#div-filters").on('change', "[name=grupo_id]", updateFamilia )
  $("#div-filters").on('change', "[name=local_id]", changeLocal )
  // 
  $("#datatable").on('draw.dt', event => setBtnsToMassiveModify() )
  // Tipo de cambio
  $(".edit-tc").on('click', editTipoCambio);
  $(".cancel-edit-tc").on('click', cancelEditTipoCambio);
  $(".update-tc").on('click', updateTipoCambio);
  $(".show-div").on('click', showDivs);
  // Editar precio
  $("table").on('click', '.edit-precios', editPrecio);
  $("table").on('click', '.cancel-edit-precios', cancelEditPrecio);
  $("table").on('click', '.update-precios', updatePrecio);
  // Actualizar precios
  $(".accion-btn").on('click', chooseOpcion);
  $(".btn-actualizar").on('click', updateMasive);
  // 
  $("table").on('keyup', 'input', makeCalculate);
  $("table").on('click', '.save', save);
  updateTipoCambioParaCalculos();
  showDivFilter();
})

H.init();