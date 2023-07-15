let estados_sunat = {
  '0001': 'El comprobante existe y está aceptado',
  '0002': 'El comprobante existe  pero está rechazado',
  '0003': 'El comprobante existe pero está de baja',
  '0011': 'El comprobante de pago electrónico no existe',
};

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
          // "td": $("[name=td]").val(),
          "fecha_emision": $("[name=fecha_emision]").val(),
          "fecha_final": $("[name=fecha_final]").val(),
          "estado_sistema": $("[name=estado_sistema] option:selected").val(),
          "estado_sunat": $("[name=estado_sunat] option:selected").val(),
        });
      }
    },
    "columns": [
      { data: 'GuiOper', searchable: false },
      { data: 'GuiOper', searchable: false, render : function(a,b,data,d){
        return data.GuiSeri + '-' + data.GuiNumee ;
      }},
      { data: 'GuiFemi', searchable: false },
      { data: 'fe_rpta', searchable: false, render : function(a,b,data,d){
        if( data.GuiEsta == "A" ){
          return "0003";
        }
        else {
          console.log(data.GuiOper + " "  +  data.fe_rpta);
          switch (data.fe_rpta) {
            case 0:
              return "0001"
            break;
            case 9:
            case "9":
              console.log( "aqui estamos", data.fe_rpta);
              return "0011" + " " +  data.fe_rpta
            break;
            default:
              return "0002" + "(" +  data.fe_rpta + ")"; 
              break;
          }
       }
      }},

      { data: 'fe_obse', searchable: false, render : function(a,b,data,d){
        if (data.GuiEsta == "A") {
          return estados_sunat["0003"];
        }
        else {
          console.log(data.GuiOper + " " + data.fe_rpta);
          switch (data.fe_rpta) {
            case 0:
              return estados_sunat["0001"];
              break;
            case 9:
            case "9":
              console.log("aqui estamos", data.fe_rpta);
              return estados_sunat["0011"];
              break;
            default:
              return estados_sunat["0002"];
              break;
          }
        }
      }},
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