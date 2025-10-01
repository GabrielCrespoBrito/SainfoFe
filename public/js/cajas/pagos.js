window.AppPago =
{
  parent: "#modalPago",
  is_init: false,
  is_static_modal: false,
  eles: {},
  urls: {},
  id: null,
  idPago: null,
  current_action: null,
  data: {
    messageTitle: {
      'create': 'Nuevo Pago',
      'ver': 'Pago',
      'edit': 'Modificar Pago',
    },
    tipospago: {
      nota: ["06"],
      bancario: ["02", "03", "04", "05"],
      efectivo: ["00", "01"],
    }
  },

  setCuentasData: function () {
    let cuentas = this.eles.banco_select.find('option:selected').data('cuentas');
    add_to_select("[name=CuenCodi]", cuentas, 'CueCodi', "CueNume", true)
  },

  calculate_resold: function (cantidad, importe) {
    let estado_pago = $("#estado_pago");

    estado_pago.removeAttr('class');
    if (cantidad > importe) {
      estado_pago
        .addClass('VUELTO')
        .text("VUELTO");
    }
    else if (cantidad < importe) {
      estado_pago
        .addClass('SALDO')
        .text("SALDO");
    }
    else {
      estado_pago
        .addClass('COMPLETADO')
        .text("COMPLETADO");
    }
  },


  showHideButton: function (show) {
    show ? $(".botones_div", this.parent).show() : $(".botones_div", this.parent).hide();
  },

  showHideCalculadora: function (show) {
    show ? $(".calculadora").show() : $(".calculadora").hide();
  },

  calculate: function () {

    console.log("calculadora actions");
    let soles = $("[name=soles]", ".calculadora");
    let soles_value = Number(soles.val());

    let dolar = $("[name=dolar]", ".calculadora");
    let dolar_value = Number(dolar.val());

    let t_cambio = $("[name=VtaTcam]", ".calculadora");
    let t_cambio_value = Number(t_cambio.val());

    let t_recibido = $("[name=totalRecibido]", ".calculadora");
    let t_recibido_value = Number(t_recibido.val());

    let t_operacion = $("[name=totalOperacion]");
    let t_operacion_value = Number(t_operacion.val());

    let importe = Number($("[data-db=VtaImpo]").val());

    if (validateIsNotNumber(soles_value) || validateIsNotNumber(dolar_value) ||
      validateIsNotNumber(t_cambio_value)) {
      t_recibido.val(0);
      t_operacion.val(0)
      return;
    }

    else {
      let dolar_operacion = (dolar_value * t_cambio_value);
      let total = soles_value + dolar_operacion;
      t_recibido.val(total);
      t_operacion.val(importe - total);
      this.calculate_resold(total, importe);
    }
  },

  callback_successPay: function (data) { console.log("defaultcallbacksuccesspay", data) },

  set_callback: function (func) {
    this.callback_successPay = func;
  },

  set_id: function (id) {
    this.id = id;
  },

  get_id: function () {
    return this.id;
  },

  set_urls: function () {
    this.urls = {
      pagos: $(this.parent).data('urlpagos'),
      save: $(this.parent).data('urlsave'),
      show: $(this.parent).data('urlshow'),
      store: $(this.parent).data('urlsave'),
      update: $(this.parent).data('urledit'),
      delete: $(this.parent).data('urlsave'),
    }
  },

  success: function (data) {
    this.eles.tipopago.prop('disabled', false);
    Helper__.set_data_form(this.parent, data, false, false, 'field', {
      'TpgCodi': true,
      'VtaNume': true,
      'CpaNume': true,
      'importe': true,
    });

    const fechaActual = new Date();
    const yyyy = fechaActual.getFullYear();
    const mm = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
    const dd = String(fechaActual.getDate()).padStart(2, '0');
    const fechaFormateada = `${yyyy}-${mm}-${dd}`;

    $("[name=fecha_pago]", this.parent).val(fechaFormateada);
    $("[name=importe]", this.parent).val(data.saldo);

    if (this.is_static_modal) {
      $(this.parent).modal({ backdrop: 'static', keyboard: false })
    }
    else {
      $(this.parent).modal();
    }
  },

  show: function () {
    this.init();
    this.defaulState()
    this.current_action = "show";

    let info = { id_factura: this.get_id(), };
    let funcs = { success: this.showForm.bind(this) };
    let url = this.urls.show.replace('XXX', this.get_id());
    ajaxs(info, url, funcs);
  },


  setNotaCreditoSelect: function (nota_credito = null) {

    try {
      this.eles.nota_credito.select2('destroy');
    } catch (e) {
      console.log(e);
    }

    this.eles.nota_credito.empty();

    if (nota_credito) {
      this.eles.nota_credito.attr({
        'data-id': nota_credito.id,
        'data-text': nota_credito.text,
      })
    }
    else { }

    this.eles.nota_credito.attr({
      'data-id': nota_credito ? nota_credito.id : '',
      'data-text': nota_credito ? nota_credito.text : ''
    });

    initSelect2("[name=nota_credito_id]");
  },

  showForm: function (data) {
    console.log("showForm", data);
    this.setTitle('Pago', data.PagOper);
    Helper__.set_data_form(this.parent, data);
    this.setNotaCreditoSelect(data.nota_credito);
    this.showHideDiv();
    this.showHideButton(false);
    this.showHideCalculadora(false);
    this.eles.tipopago.prop('disabled', true)
    // $( '[name=importe]' , this.parent).val(data.por_pagar);
    $(this.parent).modal();
  },

  editForm: function (data) {
    console.log("editForm", data);
    this.current_action = "edit";
    this.setTitle('Modificar Pago', data.PagOper);
    Helper__.set_data_form(this.parent, data);

    this.setNotaCreditoSelect(data.nota_credito);
    this.showHideDiv();
    this.showHideButton(true);
    this.showHideCalculadora(true);
    this.eles.fecha_pago.val(data.PagFech);
    this.eles.tipopago.prop('disabled', true)
    // $( '[name=importe]' , this.parent).val(data.por_pagar);
    $(this.parent).modal();
  },

  edit: function () {
    this.init();
    this.showHideCalculadora(true);

    let info = { id_factura: this.get_id(), };
    let funcs = { success: this.editForm.bind(this) };
    let url = this.urls.show.replace('XXX', this.get_id());
    ajaxs(info, url, funcs);
  },

  getTypePago: function () {
    return this.eles.tipopago.find('option:selected').val();
  },

  isTypeBancario: function () {
    return this.eles.tipopago.find('option:selected').attr('data-bancario') == "1";
  },

  getImporte: function () {
    return this.eles.importe.val();
  },

  /**
   * Mostrar u ocultar divs
   * 
   * @return void
   */
  showHideDiv() {
    let typePago = this.getTypePago();

    // if ( this.data.tipospago.bancario.includes(typePago)){
    if (this.isTypeBancario()) {
      this.eles.banco_div.show(500);
    }
    else {
      this.eles.banco_div.hide(500);
    }

    // Nota credito
    if (this.data.tipospago.nota.includes(typePago)) {
      this.eles.nota_div.show(500);
    }
    else {
      this.eles.nota_div.hide(500);
    }
  },

  setTitle(text, correlative = '') {
    this.eles.title_text.text(text);
    this.eles.title_correlative.text(correlative);
    correlative ? this.eles.title_correlative.show() : this.eles.title_correlative.hide();
  },

  defaulState: function (tipopago_default = null) {
    tipopago_default ?
      this.eles.tipopago.find(`option[value=${tipopago_default}]`).prop('selected', true) :
      this.eles.tipopago.find('option:eq(1)').prop('selected', true);

    this.eles.moneda.find('option:eq(0)').prop('selected', true)
    this.eles.baucher.val();
    this.showHideDiv();
    this.showHideButton(true);
    this.showHideCalculadora(true);
  },

  create: function (tipopago_default = null) {
    this.init();
    this.defaulState(tipopago_default);
    this.setNotaCreditoSelect(null);
    this.setTitle('Nuevo Pago')
    this.current_action = "create";

    let data = { id_factura: this.id }
    let funcs = { success: this.success.bind(this) }
    ajaxs(data, this.urls.pagos, funcs);
  },

  down: function () {
    $(this.parent).modal("hide");
  },

  successPay: function (data) {
    // console.log("se ha pagado correctamente", data, this.callback_successPay );
    this.callback_successPay(data);
    this.down();
  },


  validate: function () {
    if (validateIsNotNumber(this.getImporte())) {
      notificaciones("El importe tiene que ser un numero", "error");
      return false;;
    }

    return true;
  },


  payment: function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    // AppPagosIndex
    // AppPagosIndex
    if (!this.validate()) {
      return;
    }

    let data =
    {
      PagOper: this.idPago,

      pagoEfectivo: $('[name=soles]', this.parent).val(),
      vuelto: $('[name=totalOperacion]', this.parent).val(),

      tipopago: this.eles.tipopago.find('option:selected').val(),
      VtaOper: this.get_id(),
      VtaImpo: this.getImporte(),
      cuenta_id: null,
      moneda: this.eles.moneda.find('option:selected').val(),
      baucher: this.eles.baucher.val(),
      tipocambio: $('[name=VtaTcam]', this.parent).val(),
      fecha_pago: this.eles.fecha_pago.val(),
      nota_credito_id: this.eles.nota_credito.val(),
      fecha_vencimiento: this.eles.fecha_vencimiento.val(),
    };

    if (window.venta_rapida) {
      data.create_pdf = true;
      data.formato_impresion = $('[name=formato_impresion]', "#modalGuardarFactura").val();
    }

    // if (this.data.tipospago.bancario.includes(this.getTypePago())) {
    if (this.isTypeBancario()) {
      data.cuenta_id = this.eles.banco.find('option:selected').val();
    }

    this.eles.button_submit.addClass('disabled');

    let func_complete = function () {
      this.eles.button_submit.removeClass('disabled');
    }

    let funcs = {
      success: this.successPay.bind(this),
      complete: func_complete.bind(this)
    };


    ajaxs(data, this.getUrl(), funcs);
    return false;
  },

  getUrl: function () {
    let url;
    if (this.current_action == "create") {
      url = this.urls.store;
    }

    else {
      url = this.urls.update;
      url = url.replace('XXX', this.get_id());
    }

    return url;
  },

  events: function () {
    this.eles.button_submit.on('click', this.payment.bind(this));
    this.eles.tipopago.on('change', this.showHideDiv.bind(this));
    this.eles.banco_select.on('change', this.setCuentasData.bind(this));
    $("[name=soles],[name=dolar],[name=VtaTcam]", this.parent).on('keyup', this.calculate.bind(this))
    $("[name=moneda]", this.parent).on('change', (e) => {
      let moneda = e.target.value;
      console.log("moneda", moneda);
    });
  },

  set_eles: function () {
    this.eles.button_submit = $("#pay_factura", this.parent);
    this.eles.title = $(".title-pay", this.parent);
    this.eles.title_text = $(".title-pago .descripcion", this.parent);
    this.eles.title_correlative = $(".pay-correlative", this.parent);
    this.eles.banco_select = $("[name=BanCodi]", this.parent);
    this.eles.banco = $("[name=CuenCodi]", this.parent);
    this.eles.moneda = $("[name=moneda]", this.parent);
    this.eles.banco_div = $(".banco", this.parent);
    this.eles.nota_div = $(".nota", this.parent);
    this.eles.tipopago = $("[name=tipopago]", this.parent);
    this.eles.importe = $("[name=importe]", this.parent);
    this.eles.baucher = $("[name=NumDoc]", this.parent);
    this.eles.fecha_pago = $("[name=fecha_pago]", this.parent);
    this.eles.fecha_vencimiento = $("[name=fechaVen]", this.parent);
    this.eles.nota_credito = $("[name=nota_credito_id]", this.parent);
  },

  init: function () {
    if (!this.is_init) {
      this.set_eles();
      this.set_urls();
      this.events();
    }
  },
}


/*
  Index para mostrar los pagos de un factura
*/

window.AppPagosIndex = {

  parent: "#modalPagos",
  eles: {},
  urls: {},
  id: null,
  current_tr: null,
  is_init: false,
  tipo_pago_default: null,


  set_id: function (id) {
    this.id = id;
  },

  get_id: function () {
    return this.id;
  },

  set_urls: function () {
    this.urls = {
      pagos: $('.botones_div', this.parent).attr('data-urlpagos'),
      remove: $('.botones_div', this.parent).attr('data-urlremove')
    }
  },

  activeDesactiveNew: function (val) {
    let disabled = !Number(val);
    $(".nuevo_pago", this.parent).toggleClass('disabled', disabled);
  },

  set_data_table: function (data) {
    Helper__.set_data_form(this.parent, data);

    add_to_table(this.eles.table, data.payments,
      [
        { name: "id" },
        { name: "fecha" },
        { name: 'tipopago' },
        { name: "moneda" },
        {
          name: "tcambio", render: function (value) {
            return Number(value).toFixed(2)
          }
        },
        { name: "importe" },
        {
          name: "importe", render: function (value, index, data_, tr) {
            let btnsActions = "<a href='#' class='show-payment btn btn-default btn-xs'> <span class='fa fa-eye'></span> </a>"
            let editButton = "<a href='#' class='edit-payment btn btn-default btn-xs'> <span class='fa fa-pencil'></span> </a>"
            let deleteButton = "<a href='#' class='delete-payment btn btn-default btn-xs'> <span class='fa fa-trash'></span> </a>"

            if (data_.modify) {
              btnsActions += editButton;
            }
            if (data_.delete) {
              btnsActions += deleteButton;
            }

            return btnsActions;

          }
        }
      ]);
  },

  success: function (data) {
    this.activeDesactiveNew(data.create_new)
    this.set_data_table(data);
    $(this.parent).modal();
  },

  show: function (func_success) {
    let info = { id_factura: this.get_id(), };
    let funcs = { success: func_success.bind(this) };
    ajaxs(info, this.urls.pagos, funcs);
  },

  show_notopenmodal: function () {
    this.show(this.set_data_table);
  },

  show_openmodal: function () {
    this.show(this.success)
  },

  down: function () {
    $(this.parent).modal("hide");
  },

  set_idPago: function (e) {
    e.preventDefault();
    AppPago.set_id(this.id);
    AppPago.create(this.tipo_pago_default);
  },

  callSuccessRemove: function (data) {
    console.log("callSuccessRemove", data);
  },

  successRemove: function (saldo) {
    this.eles.saldo.text(saldo);
    this.current_tr.hide(100, function () {
      $(this).remove()
    });

    this.callSuccessRemove(saldo);
    notificaciones("Pago eliminado exitosamente", "success");
  },

  removePago: function (e) {
    if (confirm("Esta seguro que desea eliminar este pago?")) {

      this.current_tr = $(e.target).parents('tr');

      let id_pago = this.current_tr.find('td:eq(0)').text();
      let data = { id_pago: id_pago }
      let funcs = { success: this.successRemove.bind(this) }

      ajaxs(data, this.urls.remove, funcs);
    }
  },

  events: function () {
    $(".nuevo_pago", this.parent).on('click', this.set_idPago.bind(this));




    $("table", this.parent).on('click', ".show-payment,.edit-payment", function () {
      let $ele = $(this);
      let id = $ele.parents('tr').find('td:eq(0)').text();
      AppPago.set_id(id);
      $ele.is('.show-payment') ? AppPago.show() : AppPago.edit();
    });

    $("table", this.parent).on('click', '.delete-payment', this.removePago.bind(this));
    $(".indexPagos", this.parent).on('click', this.removePago.bind(this));
  },

  set_eles: function () {
    this.eles.moneda = $(".moneda", this.parent);
    this.eles.saldo = $(".cantidad_pagar", this.parent);
    this.eles.modal = $(this.parent);
    this.eles.table = $("#table_pagos", this.parent);
    this.eles.title = $(".modal-title", this.parent);
  },

  init: function () {
    if (!this.is_init) {
      this.set_urls();
      this.set_eles();
      this.events();
      this.is_init = true;
    }
  },
};