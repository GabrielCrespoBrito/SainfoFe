var tr_selects = [];
var select_all = false;
var trs_enviar;
var en_proceso = false;

function seleccionar_tr(){
  if(en_proceso){
    console.log("en proceso");
    return;
  }
  let $tr = $(this);
  let s = 'seleccionado';
  $("#datatable tbody tr").removeClass(s);
  $tr.is( '.'.concat(s) ) ? $tr.removeClass(s) : $tr.addClass(s) 

  let seleccionado = $(".seleccionado");
  let href = "#";

  if( seleccionado.length ){
    href = seleccionado.data('url');
  }

  $(".modificar-accion").attr('href' , href );
}  

function toggleSelect() {

  if(en_proceso){
    console.log("en proceso");
    return;
  }

  let $trs = $("#datatable tbody tr");
  if(select_all){
    $trs.removeClass('seleccionado');    
    select_all = false;      
  }
  else {
    $trs.addClass('seleccionado');          
    select_all = true;
  }
}

Array.prototype.first = function()
{
  return this[0]
}

function data_actual()
{
  let tr_actual = trs_enviar.first();
  console.log("tr_actual", tr_actual);
  return {
    'id_factura' : $(tr_actual).find("td:eq(3)").text()
  }
}

function completado_envio(data)
{
  $(trs_enviar.first()).removeClass('seleccionado');
  $(trs_enviar.first()).remove();    
  trs_enviar.shift();

  if( trs_enviar.length ){
    enviar_facturas();
  }
  else {
    en_proceso = false;
    console.log("no ay mas que enviar")
  }
}

function fixedNumber(v, codigo = false)
{
  return isNaN(v) ? v : codigo ? v : Number(v).toFixed(2);
}

function tdCreate( value , )
{
  return td = $("<td>" + value +  "</td>");
}

function add_boletas(data, clear = true )
{
  let tbody = $(".table_boletas tbody");
  tbody.empty();          

  for (var i = 0, x = 1; i < data.length ; i++, x++) {
    
    let d = data[i];

    let className = d.VtaEsta == "A" ? 'boleta_anulada' : '';

    console.log( 'data', data );
    let tr = $("<tr></tr>").addClass(className);

    
    let link = "<a target='_blank' href='" + d.link +  "'>" +  d.VtaOper + "</a>";

    let datos = [
      tdCreate( x ),         
      tdCreate( link ), 
      tdCreate( d.TidCodi ), 
      tdCreate( d.VtaSeri ),
      tdCreate( d.VtaNumee), 
      tdCreate( d.cliente_with.TDocCodi ) ,
      tdCreate( d.cliente_with.PCCodi ),        
      tdCreate( d.cliente_with.PCRucc ),
      tdCreate( d.VtaEsta ),
      tdCreate( d.Vtabase ),
      tdCreate( d.VtaExon ),
      tdCreate( d.VtaInaf ),
      tdCreate( d.icbper ),
      tdCreate( d.VtaIGVV ),
      tdCreate(d.VtaISC),
      tdCreate( d.VtaImpo),
    ];

    tr.append(datos).appendTo(tbody);
  }
  
}

function seleccionar_elemento( tr )
{
  tr.is('.select') ? tr.removeClass('select') : tr.addClass('select');
  return false;
}

function seleccion_elemento(e)
{
  e.preventDefault();
  console.log("seleccion");
  seleccionar_elemento($(this));

  return false;;
}

function quitar_boletas(e)
{
  e.preventDefault();
  $(".select").remove();
}

function agregar_boletas(e)
{
  e.preventDefault();
  let data = {
    fecha : $("[name=fecha_busqueda]").val(),
    id_resumen : $("[name=numero_operacion]").val(),
    docnume : $("[name=codigo_operacion]").val(),
    serie : $("[name=serie] option:selected").val(),
  }

  let funcs = { success : add_boletas };

  ajaxs( data , url_agregar_boletas , funcs )
}

function agregado_exitoso(data)
{
  notificaciones("Se han agregado las boletas", "success");
  go_listado()      
}

function get_boletas_select()
{
  let ids = [];

  $(".table_boletas tbody tr").each(function(index,dom){
    ids.push( $( 'td:eq(1)' ,this).text() );
  });    

  return ids;
}

function guardar_boleta()
{
  if( executing_ajax ){
    console.log("Ejecutando ajaxs");
    return;
  }

  if(! $(".table_boletas tbody tr").length ){
    notificaciones("Seleccione los documentos a guardar", 'danger');
    return;
  }

  if( ! confirm("Esta seguro de guardar?")  ){
    return;
  }

  let data = {
    ids : get_boletas_select(),
    id_resumen : $("[name=id_resumen]").val(),
    docnume : $("[name=codigo_operacion]").val(),      
    fecha_generacion : $("[name=fecha_generacion]").val(),
    fecha_documento: $("[name=fecha_documento]").val(),
    ticket: $("[name=ticket]").val(),
    fecha_busqueda : $("[name=fecha_busqueda]").val(),
    serie : $("[name=serie] option:selected").val(),
  };

  $(".block_elemento").show();

  let funcs = {      
    success : agregado_exitoso,
    complete : function(){
      $(".block_elemento").hide();
    }
  };

  ajaxs( data , url_guardar_boletas , funcs );
}

function enviar_sunat_boleta()
{
  if( executing_ajax ){
    console.log("Ejecutando ajaxs");
    return;
  }

  if(!confirm("Esta seguro de enviar a sunat?")){
    return;
  }

  desactivar_button(
    ".enviar_sunat",
    ".guardar",
    ".salir",
    ".agregar_boleta",
    ".quitar_boleta"
    );

  $(".btn-procesando").show();
  $(".block_elemento").show();;

  let data = {
    id_resumen : $("[name=numero_operacion]").val(),
    docnume: $("[name=codigo_operacion]").val(),
  };

  let funcs = {
    success : boletas_procesadas,            
    error : error_boletas
  };

  ajaxs( data, url_enviar_resumen , funcs );

}

function error_boletas(data)
{
  console.log("error_boletas", data);
  if(data.responseJSON.data){
    notificaciones(data.responseJSON.data , "error");       
  }

  activar_button(
    ".enviar_sunat",
    ".guardar",
    ".salir", 
    ".agregar_boleta" , 
    ".quitar_boleta"
    );

  $(".block_elemento").hide();
  $(".btn-procesando").hide();

  if( data.responseJSON.errors ){
    console.log("data.responseJSON.errors" );
    defaultErrorAjaxFunc(data);
  }
  console.log("error", data );

}

function go_listado(){

  location.href = $(".salir").attr('href');
}


function boletas_procesadas(data)
{
  notificaciones( data.data, "success" )
  setTimeout(() => {
    go_listado()  
  }, 1000);
  // validar_boleta();
}

function salir()
{
  if( executing_ajax ){
    console.log("Ejecutando ajaxs");
    return;
  }

  if( confirm("Esta seguro de salir?")  ){
    go_listado()
    console.log("salir")
  }
}     


function ticket_success(data)
{
  // console.log( "ticket_success" , data);
  // notificaciones( data.msj , "success");
  setTimeout(function(){go_listado();},1000)  
  activar_button(".enviar_sunat",".guardar",".salir",".agregar_boleta" , ".quitar_boletas");
};

function ticket_error(data)  
{
  console.log("ticket_error", data );
  if( data.responseJSON.msj ){
    notificaciones( data.responseJSON.msj , "error" );
  }
  else {
    defaultErrorAjaxFunc(data)      
  }
  go_listado();
  activar_button(".enviar_sunat",".guardar",".salir",".agregar_boleta" , ".quitar_boletas");
}

function validar_boleta()
{

  let data = {
    id_resumen : $("[name=numero_operacion]").val(),
    docnume : $("[name=codigo_operacion]").val(),      
  };

  let funcs = {
    success : ticket_success,
    error   : ticket_error,
  };

  ajaxs( data, url_validar_ticket , funcs );

  $(".block_elemento").show();
  desactivar_button(".enviar_sunat" , ".guardar" , ".salir" , ".agregar_boleta" , ".quitar_boleta");    
}    


function ver_listado_mes()
{
  location.href = $(this).val();
}

function checkedDatos(data,type,row,meta)
{ 
  let valor = Number(data);
  let icon = ['fa fa-square-o', 'fa fa-check-square-o']
  let button = "<a class='btn btn-default btn-xs'>" +
   "<span class='" + icon[valor] + "'> </span></a>";
  return button;  
};

function initDatePicker()
{
  if( $(".datepicker").length ){

    $(".datepicker").datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
    });
  }
}


function eliminar_resumen()
{
  let tr = $(".seleccionado");

  if( !tr.length ){ return; }

  let code  =  tr.find("td:eq(8)").text().trim();

  if( code == "0" ){
  notificaciones("Solo se puede  eliminar resumenes pendientes");
  return;
  }

  ajaxs(
    { 
      id_resumen : tr.find("td:eq(0)").text() , 
      docnume : tr.find("td:eq(1)").text() 
    }, 
    url_quitar_resumen , 
    { 
      success : function(){    
        notificaciones("Ha sido eliminado el resumen exitosamente", "success");
        tr.remove() 
      }
    }
  );

}


function changeTipoResumen()
{
return;
}

function events()
{ 
  $("#datatable").on('click',"tbody tr", seleccionar_tr );
  $("#select_all").on('click', toggleSelect );
  $(".agregar_boleta").on('click' , agregar_boletas );
  $(".quitar_boleta").on('click' , quitar_boletas );
  $(".eliminar-accion").on('click' , eliminar_resumen );    
  $("[name=mes]").on('change' , goToSelectLink );
  $("[name=tipo_resumen]").on('change', goToSelectLink);
  $("[name=local]").on('change' , goToSelectLink );
  $(".guardar").on('click' , guardar_boleta );
  $(".enviar_sunat").on('click' , enviar_sunat_boleta );
  $(".salir").on('click' , salir );
  $(".validar").on('click' , validar_boleta );
  $(".table_boletas tbody").on('click' , "tr" , seleccion_elemento );  
  
  $("[name=fecha_busqueda]").datepicker().on('changeDate' , function(e){
    let date = e.target.value; 
    $("[name=fecha_generacion]").val(date);
    $("[name=fecha_documento]").val(date);
  });
}

init( initDatePicker , events );