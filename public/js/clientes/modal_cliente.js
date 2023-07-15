var mensaje_success = "Se ha registrado exitosamente";
var tipo_accion = "create";
var tr_cliente = null;

function tipo_accion_modal( accion = false ){
  if( accion ){
    tipo_accion = accion;
  }        
  return tipo_accion;
}

function respuesta_sunac_datos( data )
{
  cl({data});
  activar_button('.verificar_ruc');


  let tipo = $("[name=tipo_documento] option:selected").val();
  $("#form-cliente *").removeClass('has-error');  


  if( data.success ){

    console.log("aaa");
    $("#form-cliente [name=ubigeo]" ).val(data.ubigeo);      
    $("#form-cliente [name=direccion_fiscal]" ).val(data.direccion);
    $("#form-cliente [name=razon_social]" ).val(data.razon_social);
    $("#form-cliente [name=ubigeo]" ).val(data.ubigeo);
    $("#form-cliente [name=telefono_1]").val('');
    $("#form-cliente [name=email]").val(data.email);           
  }

  else {
    $("#form-cliente [name=ruc]").parent('.form-group').addClass('has-error');  
    $("#form-cliente [name=razon_social]").val('');  
    $("#form-cliente [name=direccion_fiscal]").val('');        
    $("#form-cliente [name=ubigeo]").val('');
    $("#form-cliente [name=telefono_1]").val('');
    $("#form-cliente [name=email]").val('');

    notificaciones( data.error , 'warning' );
  }
}

function error_sunac_datos(data){

  activar_button('.verificar_ruc');
  console.log("error sunat " , data);
  notificaciones( "Error al buscar información del cliente, escriba los datos manualmente por favor", 'error' );
  $("#form-cliente [name=ruc]").parents('.form-group').addClass('has-error');        
}

function poner_codigo(codigo){
  $("#form-cliente [name=codigo]").val(codigo);            
}

function consultar_codigo(){
  let funcs = {
    success : poner_codigo,
    error : function(data){
      console.log(data,"data");
      notificaciones(data.responseText, 'error');
    }
  };

  let data = {
    tipo_cliente : $("#form-cliente [name=tipo_cliente]").val(),
  }

  ajaxs( data , url_consulta_codigo , funcs );

}

function consulta_sunat() {

  let inputRuc = $("#form-cliente [name=ruc]");    
  let tipoDoc  = $("#form-cliente [name=tipo_documento] option:selected").val();
  
  if( $.trim(inputRuc.val()).length == 0 || isNaN( inputRuc.val() ) ){
    notificaciones( "Introduzca un ruc valido", 'warning' );
    $("#form-cliente [name=ruc]").parents('.form-group').addClass('has-error');        
    return;
  }


  if( tipoDoc == 6 && inputRuc.val().length != 11 ){
    notificaciones( "El ruc tiene que contener 11 digitos", 'warning' );
    $("#form-cliente [name=ruc]").parents('.form-group').addClass('has-error');        
    return;
  }

  if( tipoDoc == 1 && inputRuc.val().length != 8  ){
    notificaciones( "El DNI tiene que contener 8 digitos", 'warning' );
    $("#form-cliente [name=ruc]").parents('.form-group').addClass('has-error');        
    return;
  }


  desactivar_button('.verificar_ruc');

  let funcs = {
    success : respuesta_sunac_datos,
    error   : error_sunac_datos,
  }

  let data = {
    numero : $("#form-cliente [name=ruc]").val(),
    tipo_documento   : $("#form-cliente [name=tipo_documento]").val(),

  }

  ajaxs( data , url_consulta_sunat , funcs );
}

function add_cliente_to_table(data){
  console.log("agregar cliente a tabla , " , data );
}

function defaultSuccessAjaxFunc(msj = mensaje_success ){
  notificaciones( msj );  
  show_modal_cliente();
  $("#form-cliente .has-error").removeClass("has-error");
}


function defaultErrorAjaxFunc(data){
  console.log( "error ajax" , data.responseJSON );
  let errors = data.responseJSON.errors;
  let erros_arr = [];
  $("#form-cliente input").parents('.form-group').removeClass('has-error');;  
  for( prop in errors ){
    for( let i = 0; i < errors[prop].length; i++  ){
      erros_arr.push( errors[prop][i] );
      $("#form-cliente input").filter("[name=" + prop  + "]").parents('.form-group').addClass('has-error');
    }
  }
  // console.log("error del ajax" , data );
  activar_button(".send_cliente_info");
  notificaciones( erros_arr , 'error' , data.responseJSON.message ); 
}

function agregarASelect( data, select, name_codi , name_text ){

  let select_agregar = $( "[name=" + select + "]" , "#form-cliente");
    select_agregar.empty();

  for( let i = 0; i < data.length ; i++ ){
    let actual_data = data[i];
    
    let option = $("<option></option>")
    .attr('value' , actual_data[name_codi] )
    .text( actual_data[name_text] )

    select_agregar.append(option);
  }
}

function cambiar_ubicodi(){
  $("#form-cliente [name=ubigeo]").val( $("#form-cliente [name=distrito] option:selected").val() );   
}


function agregate_provincia_distrito(data){

  let option_departamento = $( "#form-cliente [name=departamento] option:selected");

  option_departamento.attr({
    'data-provincias' :  JSON.stringify(data.provincias),
    'data-distritos'  :  JSON.stringify(data.distritos),
  });

  cambiar_departamento();
}


function cambiar_departamento ()
{
  return;
  let departamento     = $( "#form-cliente [name=departamento] option:selected");    
  let id_departamento  = departamento.val();
  let depar_provincias = departamento.attr('data-provincias');
  let depar_distritos  = departamento.attr('data-distritos');

  if( depar_provincias.length ){

    let provincias_agregar = eval(depar_provincias);

    agregarASelect( provincias_agregar , "provincia" , "provcodi" , "provnomb" );

    let id_provincia = $( "#form-cliente [name=provincia] option:selected").val();

    let distritos_agregar = eval(depar_distritos).filter(function(distrito){
      return id_provincia.toString() == Number(distrito.provcodi) 
    })

    agregarASelect( distritos_agregar , "distrito" , "ubicodi" , "ubinomb" );

    cambiar_ubicodi();
  }

  // ejecutar ajaxs
  else {

    let funcs = {
      success : agregate_provincia_distrito,
    };

    let data = {
      'id_departamento' : id_departamento
    }

    ajaxs( data , url_consulta_departamento, funcs );

  }
}



function cambiar_provincia (){

  let option_departamento = $("#form-cliente [name=departamento] option:selected");
  let id_provincia = $(this).val();// this.value.toString();
  let distritos = eval(option_departamento.attr('data-distritos'));

  let distritos_agregar = distritos.filter(function(distrito){
    return id_provincia.toString() == Number(distrito.provcodi)
  });

  agregarASelect( distritos_agregar , "distrito" , "ubicodi" , "ubinomb" );
  cambiar_ubicodi()
}


function quitar_errores(){
  let option_departamento = $("#form-cliente .form-group").removeClass('has-error');    
}



function cambiar_distrito (){
  cambiar_ubicodi();
}


function modal_eliminacion_cliente(){
  tr_cliente = $(this).parents("tr");    
  show_modal_eliminar_cliente()
}

function eliminar_cliente(data){
  
  show_modal_eliminar_cliente("hide");
  notificaciones("Cliente eliminado exitosamente", "success")
  tr_cliente.css('outline' , '2px solid red');
  tr_cliente.hide(1000);
  

}

function eliminacion_cliente(){

  let codigo = tr_cliente.find("td:eq(0)").text();
  let tipo = tr_cliente.find("td:eq(1)").text();

  let funcs = {
    success : eliminar_cliente
  };

  let data = {
    'codigo' : codigo,
    'tipo' : tipo,      
  };

  ajaxs( data , url_eliminar_cliente , funcs );
}

function cambiar_tipo_documento()
{
  quitar_errores();

  let tipo = $("#form-cliente [name=tipo_documento]").val();
  let ruc  = $("#form-cliente [name=ruc]");
  let button_verificar_ruc  = $("#form-cliente .verificar_ruc");    
  let cantidad_numeros = {
    "1" :  8,  // dni
    "4" :  11, // carnet de extrangeria
    "6" :  11, // ruc
    "7" :  11, // pasaporte,
  }


  console.log("cambiar_tipod" , tipo , ruc);

  if( tipo == "0" ){
    ruc
    .val("")
    .attr({
      'readonly' : 'readonly',
      'data-cantidad_digitos' : '',
    });              
  } 

  else {          
    ruc
    .removeAttr('readonly')
    .attr({
      'data-cantidad_digitos' : cantidad_numeros[tipo],
    });        
  }  

  // Solo si es el tipo de documento es ruc o dni, habilitar el boton de busqueda

  let disabledButton = !(tipo == "1" || tipo == "6");

  button_verificar_ruc.prop('disabled', disabledButton );
}

function set_or_restar_select( select , value = false  )
{
  let select_element = $("#form-cliente [name=" + select + "]");

  if( value !== false ){
    let option = select_element.find("option[value=" + value + "]");            
    option.attr("selected" , "selected" );
  } 

  else {      
    let option = select_element.find("option").removeAttr('readonly');
  }   
}

function poner_data_form( input_name , value ){
  let formElement = $("#form-cliente").find("[name=" + input_name + "]");
  if(formElement.length){

    if( formElement[0].nodeName == "INPUT" ){
      formElement.val(value);
    }

    else {
      set_or_restar_select( input_name , value );
    }

  }
}


function modal_mostrar_datos_cliente(data)
{
  console.log(
    "modal_mostrar_datos_clientes",
    data
  );

  quitar_errores();    
  cambiar_tipo_documento();
  poner_data_form( "codigo" , data.PCCodi );  
  poner_data_form("tipo_cliente", data.TipCodi);
  poner_data_form("tipo_documento", data.TDocCodi);
  poner_data_form("ruc", data.PCRucc);

  $("[name=tipo_cliente]").prop('disabled', true );
  $("[name=tipo_documento]").prop('disabled', !data.edit_doc);
  $("[name=ruc]").prop('disabled', !data.edit_doc);

  poner_data_form( "razon_social" , data.PCNomb );
  poner_data_form( "direccion_fiscal" , data.PCDire );    

  poner_data_form( "telefono_1" , data.PCTel1 );
  poner_data_form( "telefono_2" , data.PCTel2 );    
  poner_data_form( "email" , data.PCMail );    
  poner_data_form( "contacto" , data.PCCont );    

  poner_data_form( "vendedor" , data.VenCodi );    
  poner_data_form( "moneda" , data.MonCodi );    
  poner_data_form( "lista_precio" , data.LisCodi );    
  poner_data_form( "linea_credito" , data.PCLinea );    
  poner_data_form( "af_pe" , data.PCAfPe );    

  poner_data_form( "nombre_avalista" , data.PCANom );    
  poner_data_form( "ruc_avalista" , data.PCARuc );    
  poner_data_form( "direccion_avalista" , data.PCADir );    
  poner_data_form( "telefono_avalista" , data.PCATel );    
  poner_data_form( "email_avalista" , data.PCAEma );   
  
  $("#form-cliente #ubigeo")
  .select2('destroy')
  .empty()
  .attr({
    'data-value'   : "",
    'data-text' : ""
  });

  if( data.ubigeo !== null ){
    $("#form-cliente #ubigeo")
    .attr({
      'data-value'   : data.ubigeo.ubicodi,
      'data-text' : data.ubigeo.ubinomb
    });      
  }

  initSelect2(".select2", "#modalCliente" );

  $("#form-cliente [name=tipo_cliente] option:not(:selected) ").attr('readonly','readonly');

}


function modal_modificar_cliente(){

  tipo_accion_modal("edit");

  tr_cliente = $(this).parents('tr');

  let codigo = tr_cliente.find("td:eq(0)").text();
  let tipo = tr_cliente.find("td:eq(1)").text();

  let funcs = {
    success : modal_mostrar_datos_cliente
  };
  let data = {
    'codigo' : codigo, 
    'tipo_documento' : tipo,
  }

  ajaxs( data , url_consultar_cliente , funcs )

  show_modal_cliente();
}



function show_modal_eliminar_cliente( action = "show" ){
  $("#modalEliminarCliente").modal(action);
}

function show_modal_cliente( action = "show" ){
  $("#modalCliente").modal(action);
}

function modal_cliente_create()
{
  set_or_restar_select("tipo_cliente")
  tipo_accion_modal("create");
  limpiar_modal()
  cambiar_ubicodi();

  $("[name=ruc]").val(ruc_crear);

  if( ruc_crear.length == 8 ){
    $("[name=tipo_documento] option[value=1]").prop('selected','selected');
  }
  else if( ruc_crear.length == 11 ){
    $("[name=tipo_documento] option[value=6]").prop('selected','selected')
  }

  cambiar_tipo_documento();

  $("[name=tipo_cliente]").prop('disabled', false);
  $("[name=tipo_documento]").prop('disabled', false);
  $("[name=ruc]").prop('disabled', false);

  show_modal_cliente();
}


function success_create_cliente(data){    
  $("#form-cliente input[name=departamento] option:first-child").prop('selected' ,'selected');
  show_modal_cliente("hide");
  notificaciones("Cliente creado exitosamente" , "success" );
  limpiar_modal()    
  quitar_errores();
  cambiar_departamento();
  add_cliente_to_table();
  activar_button(".send_cliente_info")
  console.log("uc" , data );
  table.search(data.PCNomb).draw();
}


function crear_cliente(data){

  let ruc_input = $("#form-cliente [name=ruc]");
  let ruc_val = ruc_input.val();
  let tipo_documento = $("#form-cliente [name=tipo_documento]").val();
  let error = false;
  let error_msj = "";

  if( isNaN(ruc_val) ){      
    notificaciones("El numero de documento tienen que ser números" , "warning" );
    return; 
  }

  if( tipo_documento === "1" ){
    if( ruc_val.length != 8  ){
      error_msj =
      notificaciones("El numero de DNI tiene que tener 8 digitos" , "warning" );
      error = true;
    }  
  }

  if( tipo_documento === "6" ){
    if( ruc_val.length != 11 ){
      notificaciones("El RUC tiene que ser de 11 digitos" , "warning" );
      error = true;
    }     
  }

  if( error ){
    ruc_input.parents('.form-group').addClass('has-error');
    return;
  }

  let funcs = {
    success : success_create_cliente,
    complete : function(){
      activar_button('.send_cliente_info');
    }
  }

  $("#block_elemento").show();

  desactivar_button(".send_cliente_info")

  ajaxs( data , url_crear_cliente , funcs );
}

function check_solicitud_create_edit_cliente(){

  if( accion_default == "create" ){
    modal_cliente_create();
  }
  else if( accion_default == "edit" ) {

  }
}

function editar_informacion_cliente(data){  
  
  show_modal_cliente("hide");
  notificaciones( "Cliente Modificado exitosamente" , "success" );
  table.draw();
}

function edit_cliente(data){

  let funcs = {
    success : editar_informacion_cliente,
  };

  ajaxs( data , url_edit_cliente , funcs );
}

function crear_edit_cliente(){

  let data = $("#form-cliente").serialize();
  let data2 = getFormData($("#form-cliente"));

  // console.log("dataForm" , data2 );
  // Operación sujeta a SPOT Banco de la Nacion N° 00-013-101345

  data2.tipo_cliente = $("#form-cliente [name=tipo_cliente] option:selected").val();
  data2.tipo_documento = $("#form-cliente [name=tipo_documento] option:selected").val();
  data2.ruc = $("#form-cliente [name=ruc]").val();


  // console.log("dataFormFixed", data2);

  if( tipo_accion_modal() == "create" ){
    crear_cliente(data2);
  }

  else {      
    edit_cliente(data2);
  }

}

function events()
{
  // mostrar modal 
  $(".crear-nuevo-cliente").on( 'click', modal_cliente_create );

  // guardar nuevo cliente
  $(".send_cliente_info").on( 'click', crear_edit_cliente );

  // abrir modal de eliminación del cliente
  $("#datatable").on( 'click', ".eliminar_user", modal_eliminacion_cliente );

 // abrir modal de eliminación del cliente
  $(".aceptar_eliminacion").on( 'click', eliminacion_cliente );

 // abrir modal para modificar cliente ------------
  $("#datatable").on( 'click', ".modificar-cliente" , modal_modificar_cliente );

  // cambiar departamento
  $("#form-cliente [name=departamento]").on( 'change', cambiar_departamento );

  // cambiar provincia
  $("#form-cliente [name=provincia]").on( 'change', cambiar_provincia );

    // cambiar distrito
  $("#form-cliente [name=distrito]").on( 'change', cambiar_distrito );

   // cambiar distrito
  $("#form-cliente [name=tipo_cliente]").on( 'change', consultar_codigo );

  // cambiar distrito
  $("#form-cliente .verificar_ruc").on( 'click', consulta_sunat );    

  // cambiar tipo de documento
  $("#form-cliente [name=tipo_documento]").on( 'change', cambiar_tipo_documento );
}

function limpiar_modal(){    
  $("#form-cliente input").val("");
  $("#form-cliente #ubigeo").val("");
}


function i(){
  events()
  limpiar_modal()
  check_solicitud_create_edit_cliente();
}

i()

Helper__.init();