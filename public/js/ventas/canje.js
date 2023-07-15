class canjeApp {
  eles = {}
  hasImporte = false;
  itemsLimit = 0;
  limitSobrePasado = false;
  docsImported = {};
  docsSelected = {};

  constructor() {
    this.setElements()
    this.initDatatable = this.initDatatable.bind(this);
    this.initEvents = this.initEvents.bind(this);
    this.makeImport = this.makeImport.bind(this)
    // this.addRemoveItem = this.addRemoveItem.bind(this)
    this.hasDocSelected = this.hasDocSelected.bind(this)
    this.updateLimitBox = this.updateLimitBox.bind(this)
    // ---------------------------------------------------------------
    this.inputCheckBoxAssigned = this.inputCheckBoxAssigned.bind(this)
    this.init();
  }

  setProcessFunc = (func) => {
    this.processFunc = func;
  }


  setLimitItems = () => {
    this.itemsLimit = Number(this.eles.$numberLimitItems.attr('data-value'));
  }

  deleteItemSelected = id => delete this.docsSelected[id]

  /**
   * Funcion que procesara los documento que se hallan seleccionado para su importación
   * @returns mixed
   */
  processFunc = () => {
    for (const id in this.docsImported) {
      const documento = this.docsImported[id];
    }
  }

  setElements = () => {
    this.eles = {
      $btnModal: $("#canje_nv"),
      $modal: $("#modalCanje"),
      $table: $("#table_canje"),
      $btnImport: $(".import-canje"),
      $inputSelectAll: $(".select-all"),
      $limitBox: $(".limits-box"),
      $numberItems: $(".limits-box .value-limit.current"),
      $numberLimitItems: $(".limits-box .value-limit.max"),
      checkboxSelector: 'checkbox-selection'
    }
  }

  updateLimitBox = () => {
    const allEles = Object.assign({}, this.docsImported, this.docsSelected);
    let itemQty = 0;
    let itemsIds = {};
    Object.entries(allEles).forEach((info) => {
      const items = info[1].items;
      items.forEach(item => itemsIds[item.DetCodi] += 1
      )
    })
    itemQty = Object.keys(itemsIds).length
    this.limitSobrePasado = itemQty > this.itemsLimit;
    this.eles.$numberItems.text(itemQty);
    this.limitSobrePasado ? this.eles.$limitBox.addClass('error') : this.eles.$limitBox.removeClass('error')
  }

  init = () => {
    if (!this.eles.$modal.length) {
      return;
    }

    this.initEvents();
    this.setLimitItems();
  }

  hasDocSelected = (id, docImported = true) => {
    return docImported ?
      this.docsImported.hasOwnProperty(id) :
      this.docsSelected.hasOwnProperty(id);
  }

  openModal = () => {
    if (!this.eles.tableDatatable) {

      if ($(".tr_item").length) {
        notificaciones('Para efectuar Canje elimine los items agregados');
        return;
      }

      this.initDatatable();
    }

    this.eles.$inputSelectAll.prop('checked', false)
    this.eles.$modal.modal(true);
  }

  inputCheckBoxAssigned = id => {
    const checked = this.hasDocSelected(id)
    return `<input type="checkbox" class="checkbox-selection" value=${id} ${checked ? 'disabled=disabled' : ''}  ${checked ? 'checked' : ''} />`
  }

  makeImport = () => {
    if (Object.keys(this.docsSelected).length == 0) {
      notificaciones('Seleccione una nota de venta', 'error')
      return;
    }

    if (this.limitSobrePasado) {
      notificaciones(`El limite de Productos en las Notas de Venta es de  ${this.itemsLimit}`, 'error')
      return;
    }

    if (this.hasImporte) {
      if (!confirm('Cualquier Modificación que halla efectuado de los Productos ya cargados se perdera, si no ha modificado nada, no se preocupe')) {
        return;
      }
    }

    $("[name=producto_nombre]").attr('readonly', 'readonly');
    $("[name=producto_codigo]").attr('readonly', 'readonly');

    $("[name=guia_tipo_asoc] option[value=sin_guia]").remove();
    $("[name=guia_tipo_asoc] option[value=asociar]").remove();    
    // ------- || -------- || -------- || -------- || -------- || -------- || --------
    $("#boton_buscar").hide();
    $(".item-accion.crear").addClass('disabled');
    $(".importar_accion").addClass('disabled');
    // ------------------------------------------------------------------------------------------------------------------------
    this.docsImported = Object.assign({}, this.docsImported, this.docsSelected);
    console.log( "Importacion final" , this.docsImported );
    this.docsSelected = {};
    this.eles.$modal.modal('hide');
    this.eles.tableDatatable.draw();
    this.processFunc(this.docsImported);
    this.hasImporte = true;
    // ------------------------------------------------------------------------------------------------------------------------
  }

  addRemoveItem = (ele) => {
    const id = ele.value;

    if (this.hasDocSelected(id, false)) {
      this.deleteItemSelected(id)
    }
    else {
      this.docsSelected[id] = $(ele).parents('tr').data('info');
    }

    $(ele).parents('tr').toggleClass('selected');

    this.updateLimitBox();
  }

  changeCheckbox = event => {
    this.addRemoveItem(event.target)
  }

  selectAllItem = (event) => {
    let selectAllCheckBoxs = event.target.checked;

    if (selectAllCheckBoxs) {
      this.eles.$table.find('.checkbox-selection:not(:checked)').each((index, checkbox) => {

        checkbox.checked = true;

        const $trParent = $(checkbox).parents('tr');
        $trParent.addClass('selected');

        this.docsSelected[checkbox.value] = $trParent.data('info');

        // .push(checkbox.value);
        // this.docsSelected.push(checkbox.value);
      })
    }

    else {

      this.eles.$table.find('.checkbox-selection:checked').each((index, checkbox) => {
        if (checkbox.disabled) {
          return;
        }
        checkbox.checked = false;
        $(checkbox).parents('tr').removeClass('selected');

        // console.log("delete", checkbox.value )
        this.deleteItemSelected(checkbox.value);
        // delete this.docsSelected[checkbox.value];

      })
    }

    this.updateLimitBox();
  }

  initEvents = () => {
    this.eles.$btnModal.on('click', this.openModal)
    this.eles.$btnImport.on('click', this.makeImport)
    this.eles.$table.on('change', '.checkbox-selection', this.changeCheckbox)
    this.eles.$inputSelectAll.on('change', this.selectAllItem)
  }

  initDatatable = () => {
    this.eles.tableDatatable = this.eles.$table.DataTable({
      "processing": true,
      "serverSide": true,
      "pageLength": 100,
      "lengthChange": true,
      "ordering": false,
      'searchable': false,
      "ajax": this.eles.$table.attr('data-url'),
      "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete": function initComplete(settings, json) {
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      createdRow: (row, data, index) => {
        const $row = $(row);
        if (this.hasDocSelected(data.VtaOper)) {
          $row.addClass('selected');
        }

        $row.data('info', data);

      },
      "columns": [
        { data: 'VtaOper', orderable: false, searchable: false, render: this.inputCheckBoxAssigned },
        { data: 'VtaNume', orderable: false, searchable: false },
        { data: 'cliente_with.PCNomb', orderable: false, searchable: false },
        { data: 'VtaFvta', orderable: false, searchable: false },
        {
          data: 'VtaOper', className: 'text-right', orderable: false, searchable: false, render: (value, display, info) => {
            return `${info.moneda.monabre} <span class="strong"> ${info.VtaImpo}</span>`
          }
        },
      ]
    });
  }
}

window.canjeAppNew = new canjeApp();