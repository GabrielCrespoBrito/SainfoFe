window.executing_ajax = false;
window.func_init = [];
window.reg_number = /^-?\d*\.?\d*$/;

window.poner_data_inputs = function(
  data,
  no_adicional = true,
  adicional = null,
  name_busqueda = "data-namedb"
) {

  // console.log("poner_data_inputs", data )

  for (prop in data) {

    if (no_adicional !== true && no_adicional[prop]) {
      no_adicional[prop](data, adicional);
    }

    else {
      let ele = $("[" + name_busqueda + "=" + prop + "]");
      let val = data[prop];
      if (ele.is('[type=checkbox]')) {
        ele.prop('checked', Boolean(Number(val)))
      }
      else {
        ele.val(val);
      }
    }
  }
}


window.fixValue = function( value , decimals = 2 ){
  return Number(value).toFixed(decimals)
}

window.sanitize = function(){
}

window.isJson = function(str) {
  try {
      JSON.parse(str);
  } catch (e) {
      return false;
  }
  return true;
}

window.addDays = function(date, days) {
  const copy = new Date(Number(date))
  copy.setDate(date.getDate() + days)
  return copy
}

window.eliminateModal = function(e){  
  e.preventDefault();
  let modal = $("#modalEliminate");
  let id = $(this).attr("data-id");    
  let url = $("#formEliminate").attr('action').replace('XX' , id);

  console.log("eliminateModal" , id);

  $("#formEliminate").attr('action' , url );
  modal.modal();
}

window.goToSelectLink = function()
{
  location.href = $(this).val();
}

function isValidEmail(mail) { 
  return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
}

window.activar_button = function ()
{
  for( let i = 0; i < arguments.length; i++ ){
    $(arguments[i]).removeClass('disabled');
    $(arguments[i]).prop('disabled', false);
  }

}

Array.prototype.first = function()
{
  return this[0];
}

window.defaultSuccessAjaxFunc = function(data)
{
  console.log("success" , data );
}

window.desactivar_button = function()
{
  for( let i = 0; i < arguments.length; i++ ){
    $(arguments[i]).addClass('disabled');
    $(arguments[i]).prop('disabled', true);
  }
}


window.toggleDisabledSelect = function( ele , disabled = true){

  let $select = $(ele);

  if( disabled ){
    $select.attr('disabled', 'disabled');
  }
  else {
    $select.removeAttr('disabled');
  }
}

window.block_elemento = function( show = true , ele = '.btn-procesando' )
{
  $(ele).toggle();
}

window.loadScreen = function(show = true )
{
  show ? $(".block_elemento").show() : $(".block_elemento").hide();
}

window.set_or_restar_select = function( name_select , value = false  )
{
  let select_element = $("#form-accion [name=" + name_select + "]");

  if( value !== false ){
    var option = select_element.find("option[value=" + value + "]");            
    option.prop("selected" , "selected" );
  }  
  else {      
    var option = select_element.find("option").prop('disabled',false);
  }
}

window.set_value = function( ele , value )
{
  let $ele = $(ele);
  if( $ele.is("select") ){
    $ele
    .find("option[value=" + value + "]")
    .prop("selected" , "selected" );
  }

  else if( $ele.is(":text") || $ele.is(":hidden") ){
    $ele.is('.datepicker') ? $ele.val(value).datepicker("refresh") : $ele.val(value);
  }
}

window.isNumber = function(n) {
  if( String(n).trim() === "" ) { return false } ;
  return  /^\d*[.,]?\d*$/.test(n);
}


window.validateIsNotNumber = function( param1 , param2 = false )
{
  return !validateNumber( param1 , param2 );
}

window.validateNumber = function(num , inputName = false)
{
  let number = inputName ? $("[name=" + inputName + "]").val() : num;
  return ( number.length != 0 && reg_number.test(number) );
}


window.poner_data_form = function( data , selector_elementos = "data-field" , exceptions = {} ,  parent = false )
{
  if( !selector_elementos ){
    throw Error("tiene el selector de los elementos que ay que asignarle los valores");
  }

  let formParent = parent ? $(parent) : $("body") ;

  $( "[" + selector_elementos + "]" , formParent ).each(function(index,dom){
    let $ele = $(dom);
    let name_propiedad = $ele.attr(selector_elementos);
    let value = exceptions[name_propiedad] ? exceptions[name_propiedad](data) : get_datajson( data , name_propiedad );
    set_value( dom , value );
  })


}


/* ________________________ formularios  _______________________*/

window.add_to_select = function( select , data , name_field_value , name_field_text  , clear = true )
{
  let $select = $(select);
  if(clear){ $select.empty() }

  for( let i = 0; i < data.length ; i++ ){
    let data_actual = data[i];
    $("<option></option>")
    .attr('value' , get_datajson( data_actual , name_field_value ) )
    .text( get_datajson( data_actual , name_field_text ) )
    .appendTo( $select )
  }
}


window.set_defaultdata_form = function( data , form = "" )
{

}


/* ________________________ tables  _______________________*/
window.select_first_ele = function(table, className = 'select')
{    
  let $table = $(table);  

  if( !$table.find("tbody tr").find(".dataTables_empty").length ){
    $table.find("tbody tr").eq(0).addClass(className);
  }
}


// subir y bajar una selecciona en una tabla
window.table_select_up_down = function( table , key , className = "select" )
{
  // console.log( "table_select_up_down" , arguments );

  let tr_select = $( "tr."  + className  , table );    
  
  if( tr_select.length ){

    let tr_index = tr_select.index();
    let all_trs = tr_select.parents("table").find("tbody tr").toArray()
    
    // Abajo
    if( key == 40 ){

      if( tr_index != all_trs.length-1){    
        
        tr_select.removeClass( className );
        $( all_trs[ tr_index + 1 ]).addClass(className);           
      }
    }

    // Arriba
    else if( key === 38 ) {
      if( tr_index ){
        tr_select.removeClass(className);          
        $(all_trs[ tr_index -1] ).addClass(className);
      }        
    }
  }
  return false;
}

window.table_select_elements = function( tr , multiple = false , className = "select")
{
  var tr_ele = $(tr);
  if( tr_ele.is( '.' + className ) ){
    tr_ele.removeClass(className);
  }
  else {
    if(!multiple){
      tr_ele.parents('tbody').find("tr").removeClass(className);
    }
    tr_ele.addClass(className);
  }
}



window.eventos_predeterminados = function( tipo , ele , params = [] )
{
  switch(tipo){

    // Evento para seleccionar filas de las columnas
    case "table_select_tr" :
      var multiple    = params[0] || false;
      var className   = params[1] || 'select';
      var funcion_eje = params[2] || false;
      // console.log("table_select_tr" , ele , multiple , className , funcion_eje);
      $(ele).on( 'click' , "tbody tr" , function(e){ 
        table_select_elements(this,multiple,className);
        if( funcion_eje ) funcion_eje(this);
      });
      break;

    case "disabled_after_submit" :
      $(ele).on( 'submit' , function(e){
        $( "[type=submit]" , this ).attr('disabled',true); 
      });
      break;

    // Evento para subir y bajar la seleccion de una tabla con el teclado      
    case "table_up_down" :
      var className = params.length ? params[0] : 'select';
      $("*").on('keyup' ,  function(e){ table_select_up_down(ele,e.keyCode,className) });
      return false;
      break;

    // Datatable
    case "table_datatable" :
      var options = params.length ? params[0] : {};
      var name_table = params[1] || "table";      
      let table = $(ele).DataTable(options);
      window[name_table] = table;
      break;

   case "select2" :
      let url = params[0];
      let name_var = params[1] || "select2_ele";      
      let cache = params[2] || false;
      window[name_var] = name_var;
      // console.log("seelct2", ele,tipo,url);
      $(ele).select2({
        placeholder: "Buscar",
        minimumInputLength: 2,
        ajax: {
          url: url,
          dataType: 'json',
          data: function (par) {
            return { data: $.trim(par.term) };
          },
          processResults: function (data) {
            return { results: data };
          },
          cache: cache
        }
      });
      break;      
  }
}

/* ________________________ inicializar librerias  _______________________*/

// datepicker
window.datepicker = function( selector = '.datepicker' , format = "yyyy-mm-dd" , value_default = "")
{
  console.log("iniciarlizar datepicker");

  $(selector).datepicker({    
    autoclose: true,
    format: format,
  });
}

window.datatable_init = function( selector = '.datatable'  )
{
  $( selector ).DataTable();
}


/* ______________________________ ajaxs  ______________________________ */

// header 
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    // 'Accept': 'application/json',
    // 'CONTENT-TYPE': 'json',
  }
});


window.ajaxs_setting = function ( settings = {} , adicional = { 'defaultErrorAjaxFunc_showfield' : false })
{
  window.ajax_default = settings;
  window.defaultErrorAjaxFunc_showfield = adicional.defaultErrorAjaxFunc_showfield;
}

// funcion principal para mandar
window.ajaxs = function( data , url , funcs = {} )
{
  executing_ajax = true;
  funcs.mientras ? funcs.mientras() : null;

  let settings = {
    type : 'post',
    url  : url,  
    data : data,
    success : function(data){   
      funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
    },
    error : function(data){
      funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);       
    },
    complete : function(data){
      executing_ajax = false;        
      funcs.complete ? funcs.complete(data) : null;
    }    
  }
  
  if( window.ajax_default != undefined ){
    for( prop in window.ajax_default ){
      settings[prop] = ajax_default[prop];
    }
  }

  $.ajax(settings);  
};

/* __________________________________________________________________________  */

/* ______________________________ micenanious  ______________________________ */

// numericos
window.validate_number = function(value)
{
  return (value == "" || value.trim() == "" ) ? false : reg_number.test(value);
}

window.noti_focus = function( elemento , notificacion_mensaje = "" , notificacion_tipo = "error" )
{
  focus_element(elemento)
  notificaciones( notificacion_mensaje, notificacion_tipo );
}

window.focus_element = function(element){
  $(element).focus();
}

// #################### AJAXS ####################



window.show_hide_modal = function( id_modal , show = true , statico = false , callback = false )
{
  if( typeof show !== "boolean" ) {
    throw Error("true o false son los valores admitidos para activar o desactivar el modal, no: " + show );
  }

  let modal = $("#" + id_modal);
  if( !statico ){
    modal.modal(show ? "show" : "hide");
  }

  else {
    modal.modal({ backdrop : "static" });
  }

  callback ? callback() : null;
}

window.show_hide_element = function(element , show_element = true)
{
  show_element ? $(element).show() : $(element).hide();
}

window.defaultErrorAjaxFunc = function(data)
{    
  if( data.responseJSON ){
    let errors = data.responseJSON.errors;
    let mensaje = data.responseJSON.message;
    showError(errors, mensaje);
  }
  else {
    notificaciones( data.statusText , 'error');
  }

}

window.showError = function(errors,mensaje){
  let erros_arr = [];
  for( prop in errors ){
    for( let i = 0; i < errors[prop].length; i++  ){
      erros_arr.push( errors[prop][i] );
      if(window.defaultErrorAjaxFunc_showfield){
          $("[name=" + prop  + "]").parents('.form-group').addClass('has-error');
        } 
    }
  }
  notificaciones( erros_arr , 'error' , mensaje ); 
}


window.fixedNumber = function(value,decimals = 2)
{
  return (Math.round(value*100)/100).toFixed(2);
}

window.noti = {

  options : {

  },


  success : () => {
  },

  error: () => {
  },

  danger: () => {
  },  

}


// = function (mensaje, type = 'info', heading = '', hideEasing = "linear") {
//   // peticiones ajaxs
//   if (!isNaN(type)) {
//     type = type == 200 ? "success" : "error";
//   }

//   var info = {
//     'heading': heading,
//     'position': 'top-center',
//     'hideAfter': 3000,
//     "hideEasing": hideEasing,
//     'showHideTransition': 'slide'
//   };

//   $.toast({
//     heading: info.heading,
//     text: mensaje,
//     position: info.position,
//     showHideTransition: info.showHideTransition,
//     hideAfter: info.hideAfter,
//     hideEasing: info.hideEasing,
//     icon: type,
//     hideAfter: false,
//     "closeButton": true,
//     "progressBar": false,
//     stack: false,
//   });
// };


window.notificaciones = function(mensaje,type='info',heading='',hideEasing = "linear")
{
  // peticiones ajaxs
  if( !isNaN(type) ){
    type = type == 200 ? "success" : "error";
  }
  
  var info = {
    'heading'   : heading,
    'position'  : 'top-center',
    'hideAfter' : 3000, 
    "hideEasing": hideEasing,
    'showHideTransition' : 'slide' 
  };

  $.toast({
    heading   : info.heading,
    text      : mensaje,
    position  : info.position,
    showHideTransition : info.showHideTransition, 
    hideAfter : info.hideAfter,
    hideEasing: info.hideEasing,
    icon      : type,
    hideAfter: false,
    "closeButton": true,
    "progressBar": false,
    stack: false,
  });
};

function execute_func(func)
{
  // console.log("ejecutando funcion",func());
}

/* ______________________________ formularios ______________________________ */

// limpiar informacion de un formulario
function form_clear_data( parent, exceptions = []  )
{
  // -- // -- // -- // -- // -- 
}

// poner data de un formulario
function form_set_data()
{
}

// obtener value de un elemento
window.get_value = function(selector)
{
  let $ele = $(selector);  
  let ele  = $ele[0];
  let type = ele.type.toLowerCase();
    
  if( type  == "radio" ){
    return $ele.filter(':checked').val();
  }
  else {
    return ele.value;        
  }
}


window.getFormData = function($form){
  var unindexed_array = $form.serializeArray();
  var indexed_array = {};
  $.map(unindexed_array, function(n, i){
      indexed_array[n['name']] = n['value'];
  });
  return indexed_array;
}

// tables 
window.get_datajson = function( data , name )
{
  // console.log("get_datajson", name );
  let names = name.split(".");    
  if( names.length == 1 ){
    if( data[name] === undefined ){ throw Error("No se encuentra la columna: " + name ); }
    return data[name];
  }
  return get_datajson( data[ names[0] ] , names.slice(1).join(".") );
}

window.td_create = function(value, className = undefined )
{
  let td = $("<td>" + value +  "</td>");
  if( className != undefined ){
    td.addClass(className);
  }
  return td;
}

window.add_to_tr = function( tr , tds , add_data = false , data = null ){

  tr.empty();
  tr.append(tds);
  
  if(add_data){
    tr.data('info',data);
  }

  return tr;
}

window.create_tds = function( data , columns , tr ){

  let tds_arr = [];

  for ( let z = 0 ; z < columns.length; z++ ){

    let column = columns[z];
    let value = "";

    if( column.defaultContent != undefined ){
      value = column.defaultContent;
    }
    else {
      value = get_datajson( data , column.name );
      value = column.render == undefined ? value : column.render( value , z , data, tr );        
    }

    tds_arr.push(td_create(value, column.class_name ));
  }

  return tds_arr;
} 

window.add_to_table = function( table , data , columns = null , clear = true , add_data = false , tr_toappend = false  )
{
  // console.log( "add_to_table" , arguments );
  let tbody = $('tbody',table);
  // Si no existe tbody crearlo
  if(!tbody.length){ tbody = $("<tbody></tbody>").appendTo(table); }  
  // Limpiar tabla
  if(clear){ tbody.empty(); }
  
  // Iterar datos
  for ( let i = 0; i < data.length; i++) {
  
    // created_tr()

    let d = data[i];
    let tr = $("<tr></tr>");

    let tds = create_tds(d,columns,tr);
    let tr_process = add_to_tr( tr , tds , add_data , d );
    tr_process.appendTo(tbody);  
  }  
}


window.cl = function(){
  console.log.apply( null , arguments );
}

// funcion para inicializar otras funciones
window.init = function()
{ 
  for(let i = 0; i < arguments.length; i++  ){    
    arguments[i]();
  }
}

var Helper = (function(){
  
  return {

    // Math
    math : 
    {
      porc : function(param1, param2)
      {
        return (param1 / 100) * param2
      }
    },

    data : {
      events : function(){},
      ajaxs : {
        set_header : false,
      },
    },

  // -------- events ------------

    default_events : function(){
      
      console.log("default_events");
      // default_events
      // datatable      
      let tabledatatable = $("table[data-datatable]");
      if( tabledatatable.length ){
        if( !tabledatatable.data('url') ){
          tabledatatable.DataTable()
        }
      }

      $("*").on('click' , '.eliminate-element' , eliminateModal )


      $("*").on('click' , '.show-hide-password' , function(e){
        console.log("click show-hide-password" );
        e.preventDefault();
        let $input = $(this).parents('.input-group').find('input[type=password],input[type=text]');
        if( ! $input.length ){
          return false;
        }

        let newType = $input.attr('type') == 'password' ? 'text' : 'password';
        $input.attr('type' , newType);
        let newIcon = newType == 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
        $(this).find('i').attr('class' , newIcon );

      return false;
    });

    },

    add_events : function(func) {
      this.data.events = func;
    },

  // events predeterminados
  eventos_predeterminados: function ( tipo , ele , params = [] ){

    switch(tipo) {

      // evento para seleccionar filas de las columnas
      case "table_select_tr" :
        var multiple =    params[0] || false;
        var className =   params[1] || 'select';
        var funcion_eje = params[2] || false;
        $(ele).on( 'click' , "tbody tr" , function(e){
          table_select_elements(this,multiple,className);
          var tr_ele = $(tr);
          if( tr_ele.is( '.' + className ) ){
            tr_ele.removeClass(className);
          }
          else {
            if(!multiple){
              tr_ele.parents('tbody').find("tr").removeClass(className);
            }
            tr_ele.addClass(className);
          }
          if( funcion_eje ) funcion_eje(this);
        });
        break;

      // evento para ejecutar datatable
      case "table_datatable" :
        var options = params.length ? params[0] : {};
        var name_table = params[1] || "table";      
        let table = $(ele).DataTable(options);
        window[name_table] = table;
        break;
    }
  },


  // -------- ajaxs ------------

  ajax_header : function(){
    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  }, 

  // funcion para inicializar otras funciones
  init : function(){
    $(document).ready(function(){
      Helper.default_events();
      Helper.data.events()
      for(let i = 0; i < arguments.length; i++  ){    
        arguments[i]();
      }
    });
  },

  register : function(selector,name_var){
    window[name_var] = $(selector);
  }

} // return

})() // end


window.initSelect2 = function (selector = ".select2" , parent = false)
{
  let selects2 = $(selector);
  

  function formatState(state) {
    const option = $(state.element);    
    const class_name = option.data("class_name");    
    return $(`<span class="${class_name}">${state.text}</span>`);
  };


  if( selects2.length ){

    selects2.each(function(index,dom)
    {
      let $this = $(dom);
      let url = $this.data('url');
      let settingsSelect = $this.data('settings') || {};
      
      let settings = Object.assign({} , settingsSelect);
      settings.placeholder = settings.placeholder || $this.attr('placeholder') || "Buscar";
      settings.minimumInputLength = settings.minimuminputlength || $this.attr('data-minimuminputlength') || 2;
      
      if( $this.data('template')){
        settings.templateResult = formatState;
        settings.templateSelection = formatState;
      }

      if(url){
      settings.ajax = {
        url: url,
        dataType: 'json',
        data: function (params) {

          let parameters_aditional = $this.data('parameters') || {};
          let parameters = Object.assign(
            { 
              data: $.trim(params.term) 
            }, parameters_aditional
          );

          // let parameters {
          //   data: $.trim(params.term)
          // };
          
          console.log( "parameters" , parameters );

          return parameters;
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: false
      }
    }
      
      let parentModal = $this.parents('.modal');

      if( parentModal.length ){
        settings.dropdownParent = parentModal;        
      }
      // console.log( $this, "settings", settings)
      $this.select2(settings);


      let id = $this.attr('data-id');
      let text = $this.attr('data-text');

      if( id !== "" ){
        var newOption = new Option( text , id, false, false);
        $this.append(newOption).trigger('change');
      }

    })
  }
}

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


// Helper con el que vamos a trabajar
window.Helper__ = (function(){

  return {

    data : {

      form : {

        setInputFilter : function (textbox, inputFilter) {

          console.log( "textbox", textbox , inputFilter);

          let events = ["input", "keydown", "keyup", "mousedown", 
          "mouseup", "select", "contextmenu", "drop"];

          events.forEach(function(event) {
            textbox.addEventListener(event, function() {

              console.log("EJECUTANDO LISTENER DE EVENTO" , this.oldValue , inputFilter(this.value));

              if (inputFilter(this.value) ) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
              } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
              }
            });
          });
        },

        filters : {
          positive_negative : function(value) {
            return /^-?\d*$/.test(value); 
          },
          positive : function(value) {
            return /^\d*$/.test(value); 
          },

          positive_menor : function(value, quantity) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= quantity); 
          },

          decimals : function(value) {
            return /^-?\d*[.,]?\d*$/.test(value); 
          },

          decimals_tow : function(value) {
            return /^-?\d*[.,]?\d{0,2}$/.test(value); 
          },
        }

      },
      orderFocus : [],
      events : function(){},
      handlers : {},
      ajaxs : {
        set_header : false,
      },
    },

    // -------------- events  ---------------

    form : {

      clean : function(eleParent){
        $("input,selected").each(function(index,dom){
          let $this = $(dom);
        })
      }

    },

    notiYFocus : function( inputName , message , tipo = "error" )
    {
      $("[name=" + inputName + "]").focus();
      notificaciones( message , tipo );
    },

    setOrderFocus : function(obj){
      this.data.orderFocus = obj;
    },

    nextFocus : function(elemento)
    {
      // console.log( "elemento" , elemento );
      let inputName = this.data.orderFocus[elemento];        
      if( typeof inputName == "string" ){
        $("[name=" + inputName + "]")
        .focus()
        .select();      
      }
      else {
        inputName();
      }
    },




    events : {

      // events predeterminados aplicarse a html
      defaults : function(){

        $("table").off('mouseover' , 'input[data-filter]' , function(){

          // console.log("datainput" , this );        
            let filter = $(this).data('filter');
            let funcFilter = Helper__.data.form.filters[filter];
            // console.log(filter, funcFilter);
            // Helper__.data.form.setInputFilter( this , funcFilter );
        });


        // datatable
        let tabledatatable = $("table[data-datatable]");
        if( tabledatatable.length ){
          if( !tabledatatable.data('url') ){
            tabledatatable.DataTable()
          }
        }

        // buscar en el Datatable
        let reloader = $("[data-reloadtable]");
        if( reloader.length ){
          let table = window[ reloader.data('reloadtable') ];
          // reloadtable
          reloader.on('change' , function(){ table.draw() } );
          reloader.each(function(index,dom){
            let $this = $(this);
            if( $this.is('a,button') ){
              $this.on('click' , function(){ table.draw() } );  
            }
          })          
        }


        // select elementos de la tabla
        let tables = $("[data-selected=true]");
        if( tables.length ){
          tables.each(function(index,dom){
            let multiple = $(this).is("[data-multiple=true]");
            $(this).on( 'click' , "tbody tr" , function(e){ 
              table_select_elements(this,multiple);
            });
          });
        }

        // select2
        let selects2 = $(".select2");
        if( selects2.length ){
          initSelect2(".select2");
        }

        // nuevo formato
        let eles = $("[data-event]");

        eles.each(function(index,dom){
          let $this = $(this);
          let eventType = $this.data('event');

          switch(eventType){

            case 'datepicker' :
            if( $this.is('[disabled]')  ){
              return;
            }

            let format = $this.data('format') || 'yyyy-mm-dd';
              $this.datepicker({
                autoclose : true,
                format : format
              })
            break;

          };

        });

      }, // defaults

      add : function(func) {
        Helper__.data.events = func;
      }, // and

    }, //

    add_events : function(func) {
      this.data.events = func;
    },


  // events predeterminados
  eventos_predeterminados: function ( tipo , ele , params = [] ){

    switch(tipo) {

      // evento para seleccionar filas de las columnas
      case "table_select_tr" :
        var multiple =    params[0] || false;
        var className =   params[1] || 'select';
        var funcion_eje = params[2] || false;
        $(ele).on( 'click' , "tbody tr" , function(e){ 
          // console.log( "this_tr" ,  this);          
          table_select_elements(this,multiple,className);          
          if( funcion_eje ) funcion_eje(this);

        });
        break;

      // evento para ejecutar datatable
      case "table_datatable" :
        var options = params.length ? params[0] : {};
        var name_table = params[1] || "table";      
        let table = $(ele).DataTable(options);
        window[name_table] = table;
        break;
    }
  },

///////////////////////////////////////////////////////////////////////////

// formularios
  clean_form : function(eleParent , selectEmpty = false ){      
    
    $('input,select' , eleParent ).each(function(index,dom){ 

      let $ele = $(this),      
      nodeName = this.nodeName.toLowerCase();

      switch( nodeName ){
        case 'input' :
        case 'textarea':
          let value =  $ele.data('default') === undefined ? '' :  $ele.data('default');
          $ele.val(value) ;
          break;
        case 'select':
          if(selectEmpty){
            $ele.empty(); 
          }
          $ele.removeProp('selected'); 
        break;
      }
    });
  },

  set_data_form : function( eleParent , data , show = false , callbacks = false , dataField = 'field', ignoreField = {} ){
    
    let $parent = $(eleParent);

    $( '[data-' + dataField + ']' ,  $parent ).each(function(index,dom){

      dataField = dataField.toLowerCase();
      

      let $ele = $(this);
      let nodeName = this.nodeName.toLowerCase();
      let fieldName = $ele.data(dataField);

      if( ignoreField[fieldName] ){
        return;
      }

      let fieldValue = get_datajson(data, fieldName);
      let nodeType = this.type === undefined ? 'texto' : this.type;

      if(callbacks){
        if( callbacks[fieldName] ){
          callbacks[fieldName]($ele,fieldValue,data);
        }
      }

      else {
        // poner data
        switch( nodeType ){
          case 'texto' :
            $ele.text(fieldValue);
          break;        
          case 'text' : 
          case 'textarea':
          case 'number': 
          case 'date':
            this.value = fieldValue
            break;
          case 'select-one':
            if( fieldValue instanceof Array  ){
              $ele.empty();
              for( let i = 0; i < fieldValue.length; i++ ){
                let option = $("<option></option>")
                .text( fieldValue[i][$ele.attr('field-text')]  )
                .val(  fieldValue[i][$ele.attr('field-value')] );

                $ele.append(option);
              }
            }
            else {
              $ele.find("option[value=" + fieldValue +  "]" ).prop('selected',true);
            }
          break;
          case 'checkbox':
          break;

          case 'radio' :
            $ele.filter("[value=" + fieldValue +  "]" ).prop('checked',true)
          break;
        }
      }

      // ------------------------------------------------------------------

      // Poner en su estado original
      switch( nodeName ){
        case 'input' :
        case 'textarea':
          show ? 
          $ele.attr('disabled','disabled') : 
          $ele.removeAttr('readonly','disabled');
          break;

        case 'select':
         show ? 
         $ele.attr('disabled','disabled') : 
         $ele.removeAttr('readonly','disabled');
        break;
      };

    })


  },


///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////



  // -------- ajaxs ------------

  ajax_header : function(){
    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  }, 

  // funcion para inicializar otras funciones
  init : function(){

    let funcs = arguments;

    $(document).ready(function(){

      // ejecutar funciones iniciales
      for(let i = 0; i < funcs.length; i++ ){ funcs[i]() }

      // registrar eventos
      this.data.events(); 

      // ejecutar eventos predeterminados;    
      this.events.defaults();      

    }.bind(Helper__));
    
  },

  register : function(selector,name_var){
    // window[name_var] = $(selector);
  }

} // return

})() // end