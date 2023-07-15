function initDatable() {
  let $table = $('#datatable');

  let url = $table.data('url');

  table = $table.DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra documento"
    },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Buscar Documento');
    },
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": {
      "url": url,
      "data": function (d) {
        return $.extend({}, d, {
          "td": $("[name=td]").val(),
          "fecha_emision": $("[name=fecha_emision]").val(),
          "fecha_final": $("[name=fecha_final]").val(),
          "estado_sistema": $("[name=estado_sistema] option:selected").val(),
          "estado_sunat": $("[name=estado_sunat] option:selected").val(),
        });
      }
    },
    "columns": [
      { data: 'VtaOper', searchable: false },
      { data: 'TidCodi', searchable: false, render : function(value){
        let tidDocsText = {
          '01': 'FACTURA ELECTRONICA',
          '07': 'NOTA DE CREDITO ELECTRONICA',
          '08': 'NOTA DE DEBITO ELECTRONICA'
        };
        return "<abbr class='tid-title' title='" + tidDocsText[value] + "'> "   + value + "</abbr>";
      } },
      { data: 'VtaNume', searchable: false },
      { data: 'VtaFvta', searchable: false },
      { data: 'status_code', searchable: false },
      { data: 'status_message', searchable: false },
      { data: 'PCRucc', searchable: false },
      { data: 'PCNomb', searchable: false },
    ]
  });
}

/**
 *
 * 
 * 
status_code
status_message
 *  
 * 
 */

function initPicker(format = "yyyy-mm-dd") {
  $('.datepicker').datepicker({
    autoclose: true,
    format: format,
  });
}

function events(){
  $(".search-table").on('click' , function(e){
    e.preventDefault();
    window.table.draw();
    console.log("buscando en la tabla")
  });
}

function init() {
  initPicker();
  initDatable();
  events();
}

$(document).ready(init);