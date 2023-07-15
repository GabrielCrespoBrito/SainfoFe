$(function(){

  var tag_input;
  var id_factura = null;
  var tipo_accion = "create";
  var tr_actual = null;  
  var form_accion = "#form-accion";
  var id_elemento = null;
  var cambiar_numero_operacion = true;
  var agregar_familia = false;

  function tipo_accion_modal( accion = false ){
    if( accion ){
      tipo_accion = accion;
    }        
    return tipo_accion;
  }

  function poner_enlaces( id = "00"){    
    console.log("poner enlace a botones" , id );

    // let crear_button = $(".crear-nuevo");
    let modificar_button = $(".modificar-accion");    

    // let href_old_crear = crear_button.attr('data-href');
    let href_old_modificar = modificar_button.attr('data-href');

    // var last_position_crear = href_old_crear.lastIndexOf("/");
    var last_position_modificar = href_old_modificar.lastIndexOf("/");

    // var href_sin_nuevo_id_crear = href_old_crear.slice(0 , last_position_crear +1);
    var href_sin_nuevo_id_modificar = href_old_modificar.slice(0 , last_position_modificar +1);    

    // let nuevo_enlace_crear = href_sin_nuevo_id_crear + id;
    let nuevo_enlace_modificar = href_sin_nuevo_id_modificar + id;

    // console.log("old_href_crear y modificar" , href_old_crear , href_old_modificar);    

    // crear_button.attr('href' , nuevo_enlace_crear );
    modificar_button.attr('href' , nuevo_enlace_modificar );
  }

  function active_or_disable_button( active = true , id = null )
  {
    let modificar_button = $(".modificar-accion");    
    let eliminar_button = $(".eliminar-accion");
    let anular_button = $(".anular-accion");   
    let array_buttons = [ modificar_button , eliminar_button , anular_button ];    

    for( let i = 0; i < array_buttons.length; i++ ){
      if( active ){
        poner_enlaces(id)
        array_buttons[i].removeClass('disabled');
      }
      else {
        array_buttons[i].addClass('disabled');
      }
    }    
  }

  function active_ordisable_trfactura( active = true , tr_factura ){

    if( active ){
      $(".seleccionado").removeClass('seleccionado');            
      $(tr_factura).addClass('seleccionado');
    }    
    else {
      $(".seleccionado").removeClass('seleccionado');      
    }
  }

  a = estadoCorreo = function(mandando = true){

    let b = $(".send_correo");

    if( mandando ){

      b.removeClass('btn-success')
      .addClass('btn-default disabled')
      .text("Enviado correo");


      b.find("span")
      .removeAttr('class')
      .attr('class', 'fa fa-spin fa-spinner');    
    }

    else {

      b
      .removeClass('btn-default disabled')
      .addClass('btn-success')
      .text("Enviar");


      b.find("span")
      .removeAttr('class')
      .attr('class', 'fa fa-envelope');        
    }

  }

  function enviar_correo()
  {
    let hasta   = $(".corre_hasta").val();
    let asunto  = $(".corre_asunto").val();
    let mensaje = $(".corre_mensaje").val();
    let documentos = $(".corre_documentos").val();

    if(hasta.length > 145){
      notificaciones("La cantidad de correos excede lo permitido" , "error");      
    }

    if(  ! hasta.length ){
      notificaciones("Es necesaria llenar colocar el destinatario" , "error");
      $("[name=corre_hasta]").focus();      
      return;
    }

    var searchIDs = $(".corre_documentos:checked").map(function(){
      return $(this).val();
    }).get(); 


    let  formData = new FormData();    
    formData.append( 'id_factura' , id_factura );    
    formData.append( 'hasta' , hasta );
    formData.append( 'asunto' , asunto );
    formData.append( 'mensaje' , mensaje );
    formData.append( 'documentos' , searchIDs );

    let funcs = {
      success : successEmailSend,
      error : function(data){
        show_modal("hide", "#modalRedactarCorreo");
        $(".corre_asunto").val("");
        $(".corre_mensaje").val("");
        estadoCorreo(false);
        notificaciones("No se pudo enviar el correo, por favor revise sus datos de correo electronico" , "error");
      }
    }

    estadoCorreo();
    ajaxs( formData , url_send_email,  funcs )

    console.log("enviar correo" , hasta , asunto, mensaje , id_factura );
  }

  function successEmailSend(data)
  { 
    show_modal("hide", "#modalRedactarCorreo");
    $(".corre_asunto").val("");
    $(".corre_mensaje").val("");
    estadoCorreo(false);
    notificaciones("Correo enviado exitosamente", "success");    
    console.log( "Envio exitoso del email", data )
  }


  function add_to_table( table , data , columns , settings = {} )
  {
    function split_json( name, index = 0 )
    {
      if( name.indexOf(".") !== -1 ){
        let split_name = name.split(".");
        return isNaN(index) ? split_name : split_name[index]        
      }
      return name;
    }
    let tbody = $( "tbody" , table);
    tbody.empty();   
    // registros   
    for( let i = 0; i < data.length; i++ ){      
      let d = data[i];
      let tr = $("<tr></tr>");
      // columnas
      for( let o = 0; o < columns.length; o++ ){ 
        let columna = columns[o];      

        let column_name = split_json(columna.data);
        let column_value = d[column_name];
        let column_render = columna.render;
        column_value = column_render ? column_render( columna.data , column_value ) : column_value;

        let td = $("<td></td>").text(column_value);
        tr.append(td);
      }
      tbody.append(tr);
    }
  }


  function checkChange(column,data)
  {
    if(Number(data)){
      return "si";
    }
    else {
      return "no";
    }
  }

  function put_json(name_column , valor )
  {
    // console.log( "ejecutando email put_json" , arguments );
   let data = name_column.split(".")[1];
   return valor[data];
  }

  function successMails (data)
  {
    let mail_cliente = data.documento.cliente.PCMail;    
    let columns = [ 
      {'data' : 'DetItem'},
      {'data' : 'user.usulogi' , render : put_json },             
      {'data' : 'DetFecha'},       
      {'data' : 'DetEmail'}, 
      {'data' : 'DetPDF' , render : checkChange }, 
      {'data' : 'DetPDF' , render : checkChange }, 
      {'data' : 'DetCDR' , render : checkChange },
      {'data' : 'DetAsun' },
      {'data' : 'DetMens' },
    ];
    $('#tags').val(mail_cliente);
    $('#tags').removeTag();

    add_to_table( "#emails-enviados" , data.mails , columns );
  }

  function hasValidData(td)
  {
    return $(td).find(".fa-check-square-o").length;
  }

  function seleccionar_factura()
  {
    id_factura = $(this).parents('tr').find("td:eq(0)").text();    

    if($(this).find("a.accion_email").length ){      
      $.ajax({
        type : 'post',
        url : url_mails_enviados,        
        data : {
          'id_factura' : id_factura,
        },
        success : successMails,  
      });      
      show_modal("show", "#modalRedactarCorreo");
      return;
    }


    if( $(this).find("a.accion_pdf").length ){
      let url = url_pdf.replace("XXX",id_factura);
      window.open(url,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");      
      return;
    }

    if( hasValidData(this) ){

      if( $(this).find("a.accion_xml").length ){      
        let url = url_xml.replace("XXX",id_factura);
        window.open(url,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400"); 
        console.log("visualizar xml");
        return;
      }    
      
      if( $(this).find("a.accion_cdr").length ){
        let url = url_cdr.replace("XXX",id_factura);      
        window.open(url,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400"); 
        console.log("visualizar cdr");
        return;
      } 

    }       

    let modificar_button = $(".modificar-accion");
    let eliminar_button = $(".eliminar-accion");
    let factura_seleccionada = $(".ventas-table .seleccionado");
    let tr = $(this).parents("tr");

    if( $(tr).is('.seleccionado')) {     

      console.log("esta seleccionada");
      tr_actual = null;    
      active_ordisable_trfactura( false , tr );      
      active_or_disable_button(false)
    }

    else {
      console.log("esta no esta seleccionada");

      if( factura_seleccionada.length ){
        console.log("otra esta seleccionada");
        active_ordisable_trfactura( false , factura_seleccionada[0]  )
      }

      let id_factura = $(tr).children('td:eq(0)').text();

      active_ordisable_trfactura( true , tr );
      active_or_disable_button( true, id_factura );
    }
  }


  // notificaciones
  function notificaciones ( mensaje , type = 'info' , heading = '' ){
    
    var info = {
      'heading'   : heading,
      'position'  : 'top-center',
      'hideAfter' : 3000, 
      'showHideTransition' : 'slide' 
    };

    $.toast({
      heading   : info.heading,
      text      : mensaje,
      position  : info.position,
      showHideTransition : info.showHideTransition, 
      hideAfter : info.hideAfter,
      icon      : type
    });
  };

  function poner_error(input)
  {
    input.parents('.form-group').addClass('has-error');
  }

  function defaultErrorAjaxFunc(data) {
    console.log( "error ajax" , data.responseJSON );
    let errors = data.responseJSON.errors;
    let mensaje = data.responseJSON.message;

    let erros_arr = [];
    console.log("errors" , errors );
    for( prop in errors ){

      for( let i = 0; i < errors[prop].length; i++  ){
        erros_arr.push( errors[prop][i] );
        let form = $( form_accion );
        poner_error( $( form_accion + " input").filter("[name=" + prop  + "]") );
      }
    }
    notificaciones( erros_arr , 'error' , mensaje ); 
  }

  function ajaxs( data , url , funcs = {} ){
    $.ajax({
      type : 'post',
      url  : url,  
      data : data,
      processData: false,
      contentType: false,      
      success : function(data){
        funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
      },
      error : function(data){
        funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);
      },
      complete : function(data){
        funcs.complete ? funcs.complete(data) : null;
      }
    });
  };

  // --------------------------------------------------------------------- //


  function agregarASelect( data, select, name_codi , name_text  )
  {
    let select_agregar = $( "[name=" + select + "]");
      select_agregar.empty();

    for( let i = 0; i < data.length ; i++ ){
      let actual_data = data[i];

      let option = $("<option></option>")
      .attr('value' , actual_data[name_codi] )
      .text( actual_data[name_text] );

      select_agregar.append(option);
    }
  }



  function quitar_errores( inputs = false , form = form_accion ){
    if( inputs ){
      for( var i = 0; i < inputs.length; i++ ){        
        $( form ).find("[name=" + inputs[i] + "]").parents(".form-group").removeClass('has-error');         
      }
    }
    else {
      $( form ).find(".form-group").removeClass('has-error');    
    }
  }



  function modal_eliminacion_cliente(){
    tr_actual = $(this).parents("tr");    
    show_modal_eliminar_cliente()
  }

  function eliminar_cliente(data)
  {
    show_modal_eliminar_cliente("hide");
    tr_actual.css('outline' , '2px solid red');
    tr_actual.hide(1000);
    table.draw();  
  }

  function eliminacion_cliente(){

    let codigo = tr_actual.find("td:eq(0)").text();
    let tipo = tr_actual.find("td:eq(1)").text();

    let funcs = {
      success : eliminar_cliente
    };

    let data = {
      'codigo' : codigo,
      'tipo' : tipo,      
    };

    ajaxs( data , url_eliminar_cliente , funcs );
  }

  function set_or_restar_select( select , value = false  )
  {
    let select_element = $("#form-accion [name=" + select + "]");
    if( value !== false ){
      let option = select_element.find("option[value=" + value + "]");
      option.prop("selected" , "selected" );
    }
    else {
      let option = select_element.find("option").prop('disabled',false);
    }   
  }


  function poner_data_form( input_name , value )
  {    
    let formElement = $("#form-accion").find("[name=" + input_name + "]");
    if( formElement[0].nodeName == "INPUT" ){
      formElement.val(value);
    }
    else {
      set_or_restar_select( input_name , value );
    }
  }

  function validar_input_number(input)
  {    
    let val = input.val();
    let resp = false;

    if( val.length ){
      if( ! isNaN(Number(val)) ){
        resp = true;
      }
    }

    return resp;
  } 

  function calculos(){

    quitar_errores( ["costo","utilidad","precio_venta"] );

    let costo = $( form_accion ).find('[name=costo]');
    let utilidad = $( form_accion ).find('[name=utilidad]');
    let precio_venta = $( form_accion ).find('[name=precio_venta]');    
    
    var calcular_precio_venta = function( costo , utilidad ){            
      let costo_1porc = Number(costo.val()) / 100;   
      console.log("costo * utilidad" , costo_1porc , costo_1porc * Number(utilidad.val()).toFixed(2) );

      return Number((costo_1porc * Number(utilidad.val()) ).toFixed(2));
    };

    var calcular_utilidad = function( precio_venta, costo ){
      return Number((((Number(precio_venta.val()) / Number(costo.val())) - 1) * 100).toFixed(2));
    };  


    // calcular precio de venta tomando en cuenta el costo y la utilidad
    if( $(this).is(costo) || $(this).is(utilidad) ){      
      
      if( validar_input_number(costo) ){

        if( validar_input_number(utilidad) ){          
          let nuevo_precio_venta = calcular_precio_venta(costo , utilidad);       
          precio_venta.val( (Number(costo.val()) + nuevo_precio_venta) );
        }

        else {
          poner_error(utilidad);
          precio_venta.val(0);
        }
      }

      else {
        poner_error(costo);
        precio_venta.val(0);
        utilidad.val(0);        
      }                  
    }

    // precio de venta
    else {
      console.log("precio_venta");

      if( validar_input_number(precio_venta) ){

        if( validar_input_number(costo) ){

          let nueva_utilidad = calcular_utilidad(precio_venta , costo);
          utilidad.val( nueva_utilidad );
        }

        else {
          poner_error(costo);
        }
      }

      else {
        poner_error(precio_venta);
      }      
    }        

  } // end calculo

  function modal_modificar(){

    tipo_accion_modal("edit");
  }



  function show_modal_eliminar_cliente( action = "show" ){
    $("#modalEliminarCliente").modal(action);
  }


  function show_modal( action = "show" , modal = "#modalAccion" , backdrop = true )
  {    
    $( modal ).modal( action ); return;
  }

  function poner_value_codigo( value ){
    $( form_accion ).find("[name=codigo]").val( $.trim(value)  );
  }

  function poner_value_noperacion( value ){    
    console.log( "input_numero_operacion: " , $.trim(value) );
    $( form_accion ).find("[name=numero_operacion]").val( $.trim(value) );
  }

  // seleccionar una opción por defecto al select
  function select_value( select_name  , value ){   

    let select = $( "[name=" + select_name + "]" , form_accion );
    select.find('opcion').prop('selected',false);
    select.find("option[value=" + value + "]").prop('selected', true)
  }

  function set_data_modal( data ){

    limpiar_modal( form_accion , [setDefaultValues] );    
    quitar_errores();  

    agregar_familia = data.famcodi;

    select_value( 'grupo' , data.grucodi );    
    consultar_grupo();
    select_value( 'marca', data.marcodi );
    select_value( 'procedencia' , data.marcodi );
    $( "[name=codigo]" , form_accion ).val( data.ID );
    $( "[name=numero_operacion]" , form_accion ).val( data.ProCodi );
    $( "[name=codigo_barra]" , form_accion ).val( data.ProCodi1 );
    $( "[name=nombre]" , form_accion ).val( data.ProNomb );
    select_value('tipo_existencia', data.tiecodi );
    select_value('moneda', data.moncodi );
    select_value('unidad', data.unpcodi );
    select_value('base_igv', data.BaseIGV );
    $( "[name=igv_porc]" , form_accion ).val( data.proigvv );
    let costo = (data.moncodi === "01") ? data.ProPUCS : data.ProPUCS;
    let precio_venta = (data.moncodi === "01") ? data.ProPUCS : data.ProPUCS;
    $( "[name=costo]" , form_accion ).val( $costo );
    $( "[name=utilidad]" , form_accion ).val( data.ProMarg );
    $( "[name=precio_venta]" , form_accion ).val( data.ProMarg );

    $("[name=peso]", form_accion).val(data.ProPeso);

    $("[name=peso]", form_accion ).prop( 'disabled' , false );

    $( "[name=isc]" , form_accion ).val( data.ISC );
    $( "[name=ubicacion]" , form_accion ).val( data.proubic );
    $( "[name=stock_minimo]" , form_accion ).val( data.Promini );

    $( "[name=cuenta_venta]" , form_accion ).val( data.ctavta );
    $( "[name=cuenta_venta]" , form_accion ).val( data.ctacpra );
    $( "[name=descripcion]"  , form_accion ).val( data.Proobse );
    $( "[name=modo_uso]"     , form_accion ).val( data.prouso );
    $( "[name=ingredientes]" , form_accion ).val( data.proingre );

    $( "[name=ultimo_costo]" , form_accion ).val( data.prosto1 );
    $( "[name=cto_prom]" , form_accion ).val( data.proproms );

    $( "[name=almacen_n1]" , form_accion ).val( data.prosto1 );
    $( "[name=almacen_n2]" , form_accion ).val( data.prosto2 );
    $( "[name=almacen_n3]" , form_accion ).val( data.prosto3 );
    $( "[name=almacen_n4]" , form_accion ).val( data.prosto4 );
    $( "[name=almacen_n5]" , form_accion ).val( data.prosto5 );
    $( "[name=almacen_n6]" , form_accion ).val( data.prosto6 );
    $( "[name=almacen_n7]" , form_accion ).val( data.prosto7 );
    $( "[name=almacen_n8]" , form_accion ).val( data.prosto8 );
    $( "[name=almacen_n9]" , form_accion ).val( data.prosto9 );
    $("[name=almacen_n10]", form_accion).val(data.prosto10);
    let total = 
    Number(data.prosto1) + 
    Number(data.prosto2) + 
    Number(data.prosto3) + 
    Number(data.prosto4) + 
    Number(data.prosto5) + 
    Number(data.prosto6) + 
    Number(data.prosto7) + 
    Number(data.prosto8) + 
    Number(data.prosto9) + 
    Number(data.prosto10);

    console.log("total", total );

    $("[name=almacen_total]", form_accion).val(total);
  }


  function consultas_ajax( consulta ){

    let data = new FormData();
        
    data.append('codigo' , id_elemento );    
    data.append('grupo'  , $("#form-accion [name=grupo]").val()) ;
    data.append('familia', $("#form-accion [name=familia]").val() );
    data.append('marca'  , $("#form-accion [name=marca]").val()) ;    

    let info_consulta = {          

      // codigo
      codigo : {
        url : url_consultar_codigo,
        funcs : { success : poner_value_codigo },
        data : {}
      },

      // noperación
      noperacion : {
        url : url_consultar_noperacion,
        funcs : { success : poner_value_noperacion },
        data : data
      },

      // datos
      datos : {
        url : url_consultar_datos,
        funcs : { success : set_data_modal },
        data : data
      },




    };

    let info = info_consulta[consulta];
    ajaxs( info.data , info.url , info.funcs );
  }


  function modal_create(){      
    agregar_familia = false;
    quitar_errores();
    limpiar_modal( form_accion , [setDefaultValues] );
    tipo_accion_modal("create");            
    consultas_ajax("codigo");
    consultas_ajax("noperacion");
    show_modal();
  }

  function modal_edit()
  {
    id_elemento = $(this).parents('tr').find('td:eq(0)').text();
    tipo_accion_modal("edit");            
    consultas_ajax("datos");            
    show_modal();
  }

  function buscar_table(data){
    table.search( data.ProCodi ).draw()
  }

  function success_create(data){    
    notificaciones("Producto creado exitosamente" , "success" );
    show_modal("hide");
    limpiar_modal()    
    quitar_errores();
    // numero_operacion
    // table.search(   numero_operacion  data.ProCodi ).draw()            
    table.search( $('[name=numero_operacion]').val()).draw()
    // table.search(numero_operacion  data.ProCodi).draw()
  }


  function success_edit(data){  
    console.log("producto", data );
    notificaciones( "Producto modificado exitosamente" , "success" );
    show_modal("hide");
    table.draw()
  }



  function crear_edit()
  {
    var formData = new FormData($(form_accion)[0]);
    var imagen = $("[name=imagen]")[0].files[0];            
    var imagen_campo = imagen = "undefined" ? '' : imagen;
    formData.append('imagen', imagen_campo );

    let data = formData;
    let accion = tipo_accion_modal();    
    
    let urls = {
      "create" : url_crear,
      "edit"   : url_edit
    };

    let funcs = {      
      "create" : {
       "success" : success_create,
      },
      "edit" : {
        "success" : success_edit,
      }
    };
    //   

    let funcs_accion = funcs[accion];    
    let url = urls[accion];    

    ajaxs( data , url , funcs_accion );
  }

  function agregar_select_familias(familias){    
    agregarASelect( eval(familias) , "familia" , "famCodi" , "famNomb" );

    if( agregar_familia ){
      select_value('familia', agregar_familia );    
    } else {
      cambiar_noperacion()
    }


  }  

  function consultar_grupo()
  {
    let grupo     = $( "[name=grupo] option:selected");    
    let id_grupo  = grupo.val();
    let familias  = grupo.attr('data-familias');

    if( familias.length ){
      let familias_agregar = eval(familias);      
      agregarASelect( familias_agregar , "familia" , "id" , "famNomb" );
    }

    else {

      var formData = new FormData();
      formData.append( 'id_grupo' , id_grupo );
      
      let funcs = {
        success : agregar_select_familias, // agregate_provincia_distrito,
      };
      ajaxs( formData , url_buscar_familias, funcs );
    }


  }

  function setDefaultValues(){
    $("[data-default]" , form_accion ).each(function(){
      let default_value = $(this).attr('data-default');
      if(this.nodeName.toLowerCase() == "select"){
        $(this).find(`option[value=${default_value}]`).prop('selected', true);
      }
      else {
        $(this).val( default_value );
      }

    });
  }

  function cambiar_noperacion()
  {
    if( cambiar_numero_operacion ){
      consultas_ajax('noperacion');      
    }
  }

  /**
   * Tags permitidis d la expresión
   */
  function initTags()
  {
    var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    // What a Fucking Story so good'dam good
    tag_input = $("#tags").tagsInput({
      'unique': true,
      'minChars': 10,
      'maxChars': 60,
      'validationPattern': new RegExp(re)
    });    
  }

  function events()
  {
    // eliminar
    $("table").on( 'click', '.eliminar_elemento' , function(e){

      e.preventDefault();

      let tr = $(this).parents("tr");

      console.log("eliminar elemento");

      if(confirm("Esta seguro que desea eliminar?")){        
        let formData = new FormData();    
        formData.append( 'id' , tr.find(':eq(0)').text());    

        ajaxs( 
          formData , 
          url_eliminar , 
          {
            success : function(data){
              notificaciones("Borrado exitoso", "success");
              console.log("success",tr,data);
              tr.hide(500, function(){
                $(this).remove();
              })
            },
            error : function(data){
              console.log("erro" , data.responseJSON.message);
              notificaciones(data.responseJSON.message, "error")
          }
        });
      }
    });

    // mostrar modal 
    $("#datatable").on( 'click' , "tbody td:not('[class*=accion]')" , seleccionar_factura );    
    // mostrar modal 
    $(".crear-nuevo").on( 'click', modal_create );    

    // mostrar modal 
    $(".send_correo").on( 'click', enviar_correo );
    
    // mostrar modal 
    $("#datatable").on( 'click', '.modificar_elemento' , modal_edit );    

    // cambiar noperación
    $("[name=familia],[name=marca]","#form-accion").on('change', cambiar_noperacion );

    // consultar y agregar familia correspondiente a un grupo
    $("[name=grupo]",  "#form-accion" ).on('change', consultar_grupo );

    $("[name=costo],[name=utilidad],[name=precio_venta]" , "#form-accion" ).on( 'keyup keypress change', calculos );

    // guardar informacion 
    $(".send_info").on( 'click', crear_edit );
  }

    function limpiar_modal( form = form_accion, another_funcs = null )
    {
      $( form + " input").val("");    
      if( another_funcs  ){
        for( var i = 0; i < another_funcs.length; i++ ){
          another_funcs[i]();
        }
      }
    }  

  function headerAjax(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });   
  }

  function init()
  {
    headerAjax();
    events();
    limpiar_modal();
    initTags();
    setTimeout( function(){
      $(".table_ventas_index td").removeAttr('style');
    }, 1000 );
  }

  // Really a dont fucking know yoy are doing right now

  init();

})

// icbper