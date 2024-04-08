class modalData  {

  constructor(){
    this.id = "#modalData"
    this.modalData = $("#modalData")
  }

  getIdModal(){
    return this.id;
  }

  showModal(){
    this.modalData.modal();
    return  this;
  }

  hideModal() {
    this.modalData.modal('hide');
    return this;
  }

  getBody() {
    return this.modalData.find('.modal-body');
  }

  getHeader() {
    return this.modalData.find('.modal-title');
  }

  setTitleText(title) {
    this.getHeader().text(title);
    return this;
  }

  setSize(size){

    this
    .modalData
    .find('.modal-dialog')
    .removeClass('modal-md modal-sm modal-lg modal-xs')
    .addClass('modal-' + size);

    return this;
  }

}

let modalD = new modalData();

// console.log("documentos.js")

en_proceso = false;


function seleccionar_tr()
{
  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let $tr = $(this);
  let s = 'seleccionado';

  $tr.is('.'.concat(s)) ? $tr.removeClass(s) : $tr.addClass(s)
}


function toggleSelect(e)
{
  e.preventDefault();

  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let $trs = $(".datatable-pendiente tbody tr");
  if (select_all) {
    $trs.removeClass('seleccionado');
    select_all = false;
  }
  else {
    $trs.addClass('seleccionado');
    select_all = true;
  }
}

Array.prototype.first = function () {
  return this[0]
}

function refreshStatsPendientes(e) {
  e.preventDefault();

  $("#load_screen").show()

  let url = $("#refresh-pendientes").attr('data-route');

  let funcs = {
    success: function (data) {
      location.reload();
    },
    complete: function (data) {
      $("#load_screen").hide()
    }
  };

  ajaxs({}, url, funcs);

  return false;
}


function initDatatableVentas()
{
  let isPendienteTable = $('#datatable-documentos').is('.is-pendiente');
  let route_change_date = $('#datatable-documentos').attr('data-url-date');

  let columns = [
    { data: 'VtaOper' },
    { data: 'TidCodi', orderable: false, searchable: false },
    {
      data: 'VtaNume', orderable: false, render: function (data, type, row, meta) {
        return row.VtaSeri + "-" + row.VtaNumee;
      }
    },
    {
      data: 'VtaFvta', orderable: false, render: function (data, type, row, meta) {


        let route = route_change_date.replace('@@@@', row.VtaOper);
        let target_id = 'date-' + row.VtaOper;
        let columnFecha = `
      <input type="date" id="${target_id}" data-route="${route}" data-value="${row.VtaFvta}" class="input-modify-date" disabled name="date-modify" value="${row.VtaFvta}">
      <a href="#" class="btn-modify-date" data-target="${target_id}"> <span class="fa fa-pencil"></span> </a>
      <a href="#" class="btn-save-modify-date hide" data-target="${target_id}"> <span class="fa fa-save"></span> </a>
      <a href="#" class="btn-cancel-modify-date hide" data-target="${target_id}"> <span class="fa fa-times"></span> </a>
      `;
        return columnFecha;
      }, searchable: false
    },
    // { data: 'PCNomb', orderable: false, render: function (data) { return data.slice(0, 15).concat("...") } },
    { data: 'PCNomb', orderable: false, 'searchable': false },
    { data: 'monabre', orderable: false, searchable: false },
    { data: 'VtaImpo', orderable: false, render: fixedNumber, searchable: false, className: 'text-right' },
  ];

  if (!isPendienteTable) {
    columns.push({ data: 'VtaPago', orderable: false, render: fixedNumber, searchable: false, className: 'text-right' });
    columns.push({ data: 'VtaSald', orderable: false, render: fixedNumber, searchable: false, className: 'text-right' });
    columns.push({ data: 'alm', orderable: false, searchable: false });
    columns.push({ data: 'estado', orderable: false, searchable: false, className: 'estado' });
  }

  // Btn
  columns.push({ data: 'accion', orderable: false, searchable: false, className: 'text-right' });
  let options = {
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra documento"
    },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Nro Doc/Cliente');
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable-documentos").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "empresa_id": $("[name=empresa_id]").val(),
          "local_id": $("[name=local_id] option:selected").val(),
          "user_id": $("[name=user_id] option:selected").val(),
          "estado_sunat": $("[name=estado_sunat] option:selected").val(),
          "fecha_desde": $("[name=fecha_desde]").val(),
          "fecha_hasta": $("[name=fecha_hasta]").val(),
          "tipo_documento": $("[name=tipo_documento] option:selected").val(),
          "estado_almacen": $("[name=estado_almacen] option:selected").val(),
        });
      }
    },
    "columns": columns
  }

  if (isPendienteTable) {
    options.searching = false;
    options.lengthChange = false;
    options.pageLength = 50;
  }

  table = $('#datatable-documentos').DataTable(options);
}

function initDatatableGuias() {
  
  let isPendienteTable = $('#datatable-guias').is('.is-pendiente');
  let route_change_date = $('#datatable-guias').attr('data-url-date');

  let columns = [
    { data: 'GuiOper' },
    { data: 'GuiSeri', orderable: false, searchable: false, render: function (data, type, row, meta) {
      if(row.GuiSeri){
        console.log(row.GuiSeri + "-" + row.GuiNumee, row.GuiSeri , row.GuiNumee )
        return row.GuiSeri ? row.GuiSeri + "-" + row.GuiNumee : row.GuiNume; 
      }
      else {
        return row.GuiNume; 
      }
    }},
    { data: 'docrefe', orderable: false, searchable: false },
    { data: 'GuiFemi', orderable: false, searchable: false },
    { data: 'cli.PCNomb', orderable: false, 'searchable': false },
    { data : 'almacen' ,  render : function(param1,param2,param3,param4){
      return param1 == null ? '' : param1.LocNomb
      },
      searchable : false,   sortable : false
    }
  ];

  // Btn
  columns.push({ data: 'estado', orderable: false, searchable: false, });
  columns.push({ data: 'accion', orderable: false, searchable: false, });

  let options = {
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra guia"
    },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Nro Guia/Cliente');
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable-guias").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "empresa_id"  : $("[name=empresa_id]").val(),
          "local_id"    : $("[name=local_id] option:selected").val(),
          "estado_sunat": $("[name=estado_sunat] option:selected").val(),
          "fecha_desde" : $("[name=fecha_desde]").val(),
          "fecha_hasta" : $("[name=fecha_hasta]").val(),
          "tipo_guia"   : $("[name=tipo_guia] option:selected").val(),
          "formato_guia": $("[name=formato_guia] option:selected").val(),
        });
      }
    },
    "columns": columns
  }

  if (isPendienteTable) {
    options.searching = false;
    options.lengthChange = false;
    options.pageLength = 50;
  }

  table = $('#datatable-guias').DataTable(options);
}


function initDatatableResumens() {

  let isPendienteTable = $('#datatable-guias').is('.is-pendiente');

  let columns = [
    { data: 'NumOper', orderable: false, searchable: false },
    { data: 'DocNume', orderable: false, searchable: false },
    { data: 'DocFechaE', orderable: false, searchable: false },
    { data: 'DocFechaEv', orderable: false, searchable: false },
    { data: 'DocDesc', orderable: false, searchable: false },
    { data: 'DocTicket', orderable: false, searchable: false },
    { data: 'accion', orderable: false, searchable: false },
    { data: 'btn', orderable: false, searchable: false, }
  ];

  let options = {
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra guia"
    },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Nro Guia/Cliente');
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable-resumenes").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "empresa_id": $("[name=empresa_id]").val(),
          "local_id": $("[name=local_id] option:selected").val(),
          "estado_sunat": $("[name=estado_sunat] option:selected").val(),
          "fecha_desde": $("[name=fecha_desde]").val(),
          "fecha_hasta": $("[name=fecha_hasta]").val(),
          "tipo_resumen": $("[name=tipo_resumen] option:selected").val(),
        });
      }
    },
    "columns": columns
  }

  if (isPendienteTable) {
    options.searching = false;
    options.lengthChange = false;
    options.pageLength = 50;
  }

  table = $('#datatable-resumenes').DataTable(options);
}

function initDataTable() 
{
  if( $('#datatable-documentos').length ){
    initDatatableVentas();    
  }

  if ($('#datatable-guias').length) {
    initDatatableGuias();
  }

  if ($('#datatable-resumenes').length) {
    initDatatableResumens();
  }
}

function data_actual() {
  let tr_actual = trs_enviar.first();
  return {
    'id_factura': $(tr_actual).find("td:eq(0)").text(),
    'empresa_id': $("[name=empresa_id]").val(),
    'docnume': $(tr_actual).find("td:eq(1)").text(),

  }
}

function completado_envio(data)
{
  let mensaje;

  console.log( "data", data );

  if ( data.responseJSON) {

    if (data.responseJSON.data ){
      mensaje = data.responseJSON.data;
    }
    else {
      mensaje = data.responseJSON.message;
    }
  }
  else {
    if( data.responseJSON.errors ){
      mensaje = data.responseJSON.errors.error[0];
    }
    else {
      mensaje = "Error envio";
    }

  }

  $(trs_enviar.first()).removeClass('seleccionado');

  if (data.status == 200 || data.status == 1) {
    notificaciones(mensaje, "success");
    $(trs_enviar.first()).remove();
  }
  else {
    notificaciones(mensaje, "error");
  }

  trs_enviar.shift();

  if (trs_enviar.length) {
    enviar_facturas();
  }
  else {
    activar_button(".enviar-sunat", "#select_all");
    $("#load_screen").hide();
    en_proceso = false;
    table.draw();
  }
}


function enviar_facturas() 
{
  desactivar_button(".enviar-sunat", "#select_all");
  $("#load_screen").show();

  let data = data_actual();
  let funcs = {
    complete: completado_envio,
    error: function (d) {
      console.log("error", d);
    }
  };

  let url_enviar_sunat = $(".datatable-pendiente").attr('data-url-sunat');

  ajaxs(data, url_enviar_sunat, funcs);
}

function enviar_sunat(e) {
  e.preventDefault();

  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let trs = $(".datatable-pendiente tbody tr.seleccionado");

  if (trs.length) {
    en_proceso = true;
    trs_enviar = trs.toArray();
    enviar_facturas()

  }
}

function eliminarDocumento(e)
{
  e.preventDefault();

  let $input_password = $('[name=password_admin]', "#modalData");
  let password = $input_password.val().trim();
  // let password = "1234";
  let url = $(this).attr('data-url');

  if( password.length == 0){
    $input_password.focus();    
    notificaciones('La contraseña de administrador es requerido para eliminar un documento', 'error');
    return;
  }

  if(!confirm('Esta seguro que desea eliminar este documento?')){
    return;
  }

  let funcs = {
    success: function (content) {
      // ---------
      notificaciones('Eliminación de documento exitosa','success');
      modalD.hideModal();
      table.draw();
      // ---------
    }
  }

  ajaxs({
    'empresa_id': $("[name=empresa_id]").val(),
    'password_admin': password,
  }, url, funcs);

  return false;

}


function eliminarPdfDocumento(e) {
  e.preventDefault();


  if( !confirm("Esta seguro que desea eliminar el PDF de este documento?") ){
    return;
  }

  $("#load_screen").show();

  let url = $(this).attr('data-url');  
  let funcs = {
    success: function (content) {
      notificaciones('Eliminación de pdf exitosa', 'success');
      modalD.hideModal();
      table.draw();
    }, complete : function(data){
      $("#load_screen").hide();
    }
  }

  let recreate = $('[name=re_created]', "#modalData").is(':checked');

  ajaxs({
    'empresa_id': $("[name=empresa_id]").val(),
    're_create': Number(recreate),
  }, url, funcs);

  return false;
}





function showOptionsDocument(e)
{
  e.preventDefault();

  let $this = $(this);

  let funcs = {

    success : function(content){

      console.log('content', content);

      modalD.setSize('sm');
      
      modalD.
      getBody()
      .empty()
      .append(content)
      
      modalD
      .showModal()
      .setTitleText('Acciones')
    }

  }

  let data = {
    'type' :  $this.parents('table').attr('data-type'),
    'id': $this.parents('tr').find("td:eq(0)").text(),
    'docnume': $this.parents('tr').find("td:eq(1)").text(),
    'empresa_id': $('[name=empresa_id]').val(),
  }

  let url = $(this).attr('data-url');

  ajaxs( data , url , funcs );

  return false;
}




function changeEmpresa()
{
  let data = {
    'empresa_id': $('[name=empresa_id]').val(),
  }

  let url = $('[name=empresa_id]').attr('data-url-change');

  ajaxs(data, url, {});
}

function events()
{
  $("[name=empresa_id]").on('change', function () {
    changeEmpresa();
    consultLocals(true);
  })

  $("#modalData").on('click', '.eliminar-documento', eliminarDocumento )

  $("#modalData").on('click', '.eliminar-documento-pdf', eliminarPdfDocumento)


  function sendValidacionResumen(e)
  { 
    e.preventDefault();
    $("#load_screen").show();

    let $this = $(this);
    let funcs = {
      success : function(data){
        notificaciones(data.message,'success');
      },
      complete: function (content) {        
        modalD.hideModal();
        $("#load_screen").hide();;
        table.draw();
      }
    }
    let data = {
      'type': $this.parents('table').attr('data-type'),
      'tipo_validacion': $("#modalData").find('[name=tipo_validacion]:checked').val(),
      'id': $this.parents('tr').find("td:eq(0)").text(),
      'docnume': $this.parents('tr').find("td:eq(1)").text(),
      'empresa_id': $('[name=empresa_id]').val(),
    }
    let url = $this.attr('data-url');

    ajaxs(data, url, funcs);    

    return false;

  }

  $("[name=empresa_id],[name=local_id],[name=user_id],[name=estado_sunat],[name=tipo_documento],[name=fecha_desde],[name=fecha_hasta],[name=estado_almacen],[name=tipo_guia],[name=formato_guia],[name=tipo_resumen]").on('change', function () {   
    if(table){
      table.draw();
    }
  })

  $("[data-reload]").on('change', function () {
    if (table) {
      table.draw();
    }    
  })

  $("#refresh-pendientes").on('click', refreshStatsPendientes)

  $(".datatable-pendiente").on('click', "tbody tr", seleccionar_tr);

  $("#select_all").on('click', toggleSelect);
  
  $(".enviar-sunat").on('click', enviar_sunat);

  // Modificar valor del input de fecha
  $("#datatable-documentos").on( 'click', ".btn-modify-date", activeInputToModify );
  $("#datatable-documentos").on( 'click', ".btn-cancel-modify-date", cancelModifyInput );
  $("#datatable-documentos").on('click', ".btn-save-modify-date", saveModifyInput);

  $(".datatable-pendiente").on('click', ".btn-show-options", showOptionsDocument );


  $("#modalData").on('click', ".validar-resumen", sendValidacionResumen );  
}

function activeInputToModify(e)
{
  e.preventDefault();

  let $btnModify = $(this);
  let $input = $btnModify.siblings('input');
  let $btnCancelModify = $btnModify.siblings('.btn-cancel-modify-date');
  let $btnSaveModify = $btnModify.siblings('.btn-save-modify-date');

  $input.removeAttr('disabled');
  $btnModify.addClass('hide');
  $btnCancelModify.removeClass('hide');
  $btnSaveModify.removeClass('hide');

  return false;
}


function cancelModifyInput(e) {
  e.preventDefault();

  let $btnCancelModify = $(this);
  let $input = $btnCancelModify.siblings('input');
  let $btnModify = $btnCancelModify.siblings('.btn-modify-date');
  let $btnSaveModify = $btnCancelModify.siblings('.btn-save-modify-date');

  $input.prop('disabled', true)
  $input.val($input.attr('data-value'));

  $btnCancelModify.addClass('hide');
  $btnSaveModify.addClass('hide');
  $btnModify.removeClass('hide');

  return false;
}



function saveModifyInput(e)
{
  e.preventDefault();

  //
  let $btnSaveModify = $(this);
  let $input = $btnSaveModify.siblings('input');
  let $btnModify = $btnSaveModify.siblings('.btn-modify-date');
  let $btnCancelModify = $btnSaveModify.siblings('.btn-cancel-modify-date');

  let url = $input.attr('data-route');

  let data = {
    empresa_id: $("[name=empresa_id]").val(),
    date : $input.val()
  };
  

  let funcs = {
    success : function(data)
    {
      $input.attr('data-value', data.new_value );
      $input.prop('disabled', true)      
      $btnModify.removeClass('hide');
      $btnCancelModify.addClass('hide');
      $btnSaveModify.addClass('hide');
      notificaciones('Notificacion de Fecha Exitosa', 'success' );
    },

    complete  : function(data)
    {
      console.log( "complete", data );
    }
  };


  ajaxs( data , url , funcs );



  return false;
}

function initFuncs() {
  initSelect2();
}

function initPicker(format = "yyyy-mm-dd") {
  $('[name=fecha_desde],[name=fecha_hasta]').datepicker({
    autoclose: true,
    format: format,
  });
}

window.consultLocals = consultLocals;

window.init(
  initFuncs, 
  events, 
  consultLocals, 
  initDataTable, 
  initPicker
);