var tr_pago;

function initDatable()
{
  window.table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "searching" : false,
    "paging" : false,
    "ajax": { 
      "url" : url_search,  
      "data": function ( d ) {
       return $.extend( {}, d, getData() );
      }      
    },
    "columns" : [      
      { data : 'cliente_with.PCRucc' },
      { data : 'cliente_with.PCNomb' },
      { data : 'VtaFvta' },
      { data : 'VtaFVen' },
      { data : 'VtaOper' },
      { data : 'TidCodi' },
      { data : 'VtaNume' },
      { data : 'VtaSald' },
      { data : 'VtaTota' },
      { data : 'VtaPago' },
      { data :'User_Crea'},
      { data : 'accion' , defaultContent : '<a href="#" class="pagar btn btn-xs btn-default"> Pagar</a>'  },
    ]
  });
}

function getData(){
  return {
    "cliente" : document.getElementById("cliente_ruc").value,
    "user" : $("[name=user] option:selected").val(),
    "tipo" : $("[name=tipo] option:selected").val(),
    "formato" : $("[name=formato] option:selected").val(),
  }
}


function setSelectValue(data){
  let value = data.params.args.data.data.PCCodi; 
  $("[name=corre_hasta]").val(data.params.args.data.data.PCMail);
  $("#cliente_ruc").val(value);
  table.draw(); ;
}


function show_reporte(data){

}

function process_reporte(){

  if( $(".dataTables_empty").length ){
    notificaciones("No ay ninguna deuda", "error");
    return;
  }

  let url = new URL(url_reporte);
  let params = new URLSearchParams(url.search.slice(1));

  let data = getData();
  url.searchParams.append('tipo' , data.tipo );
  url.searchParams.append('cliente' , data.cliente );
  url.searchParams.append('user' , data.user );
  url.searchParams.append('formato' , data.formato );
  url.searchParams.append( 'agrupacion' , $("[name=agrupacion]:checked").val());
  
  window.open( url.href,"_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
}



function preparar_modal_pago(data)
{
  console.log("preparar_modal_pago", data.pagos );
  $(".moneda").text(data.venta.MonCodi);
  $(".cantidad_pagar").text(data.venta.VtaSald);

  add_to_table("#table_pagos", data.pagos , 
  [
    { name : "PagFech" },
    { name : "PagOper" },
    { name : "TpgCodi" },
    { name : "MonCodi" },
    { name : "PagTCam" },
    { name : "PagImpo" }       
  ]);

  show_hide_modal("modalPagos", true);
}

function getTrSelect()
{
  let tr_select = $("#modalPagos .select");
  if( tr_select.length ){
    tr_pago.ele = tr_select;
    tr_pago.id_pago = tr_select.find("[data-campo=id_pago]").text();
  }
  return tr_select.length;
}

function prepare_verpago()
{
  let data = {
    id_pago : tr_pago.id_pago
  }

  let funcs = {
    success: poner_data_pago
  };

  ajaxs( data , url_data_pago , funcs )

  show_modal("show" , "#modalPago");
}

function prepare_mailmodal()
{  
  if($("[name=cliente_ruc]").val() == ""){
    notificaciones("Tiene que seleccionar un cliente primero", "error");
    return;
  }
  if( $(".dataTables_empty").length ){
    notificaciones("No ay deudas que enviar", "error");
    return;    
  }


  show_hide_modal("modalMail", true);
}


function success_email(data){
  notificaciones("Email enviado satisfactoriamente", "success");
  show_hide_modal("modalMail", false);
}

function send_correo(){
  let email = $("[name=corre_hasta]").val();
  let asunto = $("[name=corre_asunto]").val();
  let mensaje = $("[name=corre_mensaje]").val();

  if(isValidEmail(email)){
    // ------------------ \\
    ajaxs({
      email : email,
      asunto : asunto,      
      mensaje : mensaje,
      tipo : $("[name=tipo] option:selected").val(),
      user : $("[name=user] option:selected").val(),
      ruc : $("[name=cliente_ruc]").val(),      
    }, url_send_email ,
      {
        success : success_email,
      }
    )
    // ------------------ \\
  }
  else {
    notificaciones("Tiene que poner una dirección de email valida", "success");
  }
}


function events()
{
  // eventos_predeterminados("table_select_tr" , "#table_pagos" );  
  eventos_predeterminados("select2" , "#cliente" , [ url_cliente  ]  );
  $("[name=user],[name=tipo]").on('change', function(){ table.draw() });
  $('#cliente').on('select2:selecting' , setSelectValue );
  $(".imprimir").on('click', process_reporte  );
  $(".send_correo").on('click', send_correo );
  
  // $("#datatable").on('click', ".pagar" , function(){    
  //   $(this).parents('tr').find("td:eq(4)").text();
  //   show_modal_pagos($(this).parents('tr').find("td:eq(4)").text())
  // });
  $(".enviar").on('click', prepare_mailmodal )
}

init(initDatable,events);
