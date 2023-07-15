$(document).ready(function(e){
  var IGV_VALUE = 1.18
  var modal_cliente = "#modalSelectCliente";
  var executing_ajax = false;  
  var modal_producto = "#modalSelectCliente";
  var modal_factura = "#modalSelectFactura";
  var modal_tipodocumento = "#modalSelectTipoDocumentoPago";
  var modales_select = $(".modal-seleccion");
  var table_clientes = $("#datatable-clientes");    
  let tipo_guardado = null;
  var items_agregate = [];  
  var action_item = "create";
  let reg_number = /^-?\d*\.?\d*$/;
  var table_items = $("#table-items");      
  var current_product_data = null;  
  var show_deuda = false;
  var totales = {

  }
  var focus_orden = { 
    'cliente_documento': 'forma_pago',
    'forma_pago'       : 'moneda',
    'moneda'           : 'tipo_cambio',
    'tipo_cambio'      : 'fecha_emision',
    'fecha_emision'    : check_tipo_documento,  
    'fecha_vencimiento': check_tipo_documento,    
    'producto_nombre'  : verificar_producto_unidad,
    'producto_unidad'  : 'producto_cantidad',
    'producto_cantidad': 'producto_precio',
    'producto_precio'  : 'producto_dct',        
    'producto_dct'  : agregar_item,        
    'producto_igv'        : agregar_item,        
    'producto_isc'        : agregar_item,        
    'producto_percepcion' : agregar_item,        
    "ref_documento"    : verifiy_tipo_documento,
    "ref_serie"        : verifiy_tipo_serie,
    "ref_numero"       : verifiy_factura_number,
    "ref_fecha"        : 'ref_motivo',    
    "ref_motivo"       : 'producto_nombre',
  };


  function agregar_deudas_info(deudas)
  {
    if( deudas.data.length ){
      poner_data_inputs( deudas.cliente , true, null , 'data-n')
      poner_data_inputs( deudas , true, null , 'data-n')      
      console.log(deudas);
      let tbody = $("#table_deuda");        
      let deudas_data = deudas.data;
      for (var i = 0; i < deudas_data.length; i++) {
        let tdvtaadoc = tdCreate( deudas_data[i].vtaadoc  , false );
        let tdVtaFvta = tdCreate( deudas_data[i].VtaFvta  , false );
        let tdVtaFVen = tdCreate( deudas_data[i].VtaFVen  , false );
        let tdmoncodi = tdCreate( deudas_data[i].moneda  , false );
        let tdVtaImpo = tdCreate( deudas_data[i].VtaImpo  , false );
        let tdVtaPago = tdCreate( deudas_data[i].VtaPago  , false );
        let tdVtaSald = tdCreate( deudas_data[i].VtaSald  , false );
        let tr = $("<tr><tr>").append(tdvtaadoc,tdVtaFvta,tdVtaFVen,tdmoncodi,tdVtaImpo,tdVtaPago,tdVtaSald);
        tbody.append(tr);
      }
      show_modal("show" , "#modalDeudas");
    }
    else {
      modal_guardar();
    }
  }

  //166101.6949152542

  function calcular_item( data ){
    let dcto = data.DetDcto;    
    let igv_item = data.DetIGVP == "0" ? "0" : (Number(data.DetPrec) / Number(IGV_VALUE));    
    let resultado = {
      descuento : 0,
      valor_igv_x_item : igv_item, 
      valor_igv_total : igv_item * Number(data.DetCant), 
      valor_venta_total : 0,
      igv : 0,      
      valor_venta : 0,
    };


    // Calcular descuento 
    console.log("!")
    if( data.DetDcto == "0" ){      
      resultado.descuento = dcto;
      console.log("DESCUENTO DE 0" , dcto);
    }
    else if( data.DetIGVP == "0" ){  
      console.log("porcentaje de IGVP DE 0" , dcto);
      resultado.descuento = Number(data.DetImpo / 100) * Number(dcto)
    }
    else {
      console.log("porcentaje de IGVP DE MAS DE 0" , dcto);
      resultado.descuento = (resultado.valor_igv_total / 100) * Number(dcto);
    }

    console.log( "descuento calculado" , resultado.descuento );

    // Valor Venta Bruto || Gravada
    resultado.valor_venta_total =  resultado.valor_igv_total - resultado.descuento;

    // IGV 
    resultado.igv = resultado.valor_venta_total * 0.18;


    return resultado;
  }

  function sum_cant()
  {
    let info = {
      gravadas  : 0,
      inafectas : 0,
      exoneradas: 0,
      gratuitas : 0,
      descuento : 0,
      pagado    : 0,      
      isc       : 0,
      igv       : 0,
      total_documento : 0,
      percepcion: 0,
      total_importe : 0,
      total_cantidad : 0,
      total_peso : 0,      
    };

    let data;

    $("#table-items tbody tr").each(function(index,dom){

      data = JSON.parse($(dom).attr('data-info'));            
      let precio = Number(fixedValue(data.DetPvta));

      // console.log("Data del item" , data);
      let oper_resultados = calcular_item(data);

      switch (data.DetBase) {
        case 'GRAVADA' :                  
          info.gravadas += oper_resultados.valor_venta_total;
        break;
        case 'INAFECTA' :
          info.inafectas += precio; 
        break;
        case 'EXONERADA' :
          info.exoneradas += precio;
        break;
        case 'GRATUITA' :
          info.gratuitas += precio;
        break;
      }      

      info.total_cantidad += Number(fixedValue(data.DetCant));
      info.total_peso += Number(fixedValue(data.DetPeso));
      info.descuento += oper_resultados.descuento;
      info.igv += oper_resultados.igv;
      info.total_importe += data.DetBase == 'GRATUITA' ? 0 : Number(fixedValue(data.DetImpo));   

    });

    info.total_documento = info.total_importe;

    // console.log(info, data);

    return info;
  }

  function cantidad_letras(num)
  {
    $(".cifra_cantidad").text( NumeroALetras(num) );
  }

  function poner_totales_cant()
  {  
    let info = sum_cant();        
    for( prop in info ){
      let ele = $("[data-name=" + prop + "]");
      // console.log("error poniendo en" , prop , info[prop] );
      ele.val( fixedValue(info[prop])  );
    }    

    cantidad_letras(info.total_importe);
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
      icon      : type,
      stack: false
    });
  };

  function tdCreate(inputNameOrValue,getFromInput=true,campo="")
  {
    let value;
    let td = $("<td></td>");
    if( getFromInput ){
      value = $("[name=" + inputNameOrValue + "]").val()
    }
    else {
      value = inputNameOrValue;  
    }

    return td
    .text(value)
    .attr("data-campo" , campo);
  }

  function serie_documento_ok(data)
  {
    console.log("serie buscada",data);
    $("[name=ref_numero]").focus();
  };

  function serie_documento_error(data)
  { 
    notificaciones("Serie documento no encontrado", "danger");
    console.log("serie error" , data);
  };

  function verifiy_factura_number()
  {
    if(! verifyInputCliente() ){
      notiYFocus("cliente_documento" , "Seleccione un cliente");
      return;
    }    

    let data = {
      codigo: $("[name=ref_serie]").val()
    };
    let funcs = {
      success : serie_documento_ok,
      error : serie_documento_error,
    };

    ajaxs( data , url_verificar_serie , funcs);
    // console.log("verificando numero de modal");    
    show_modal("show", modal_factura );
  }

  function verifiy_tipo_serie(){

    if(! verifyInputCliente() ){
      notiYFocus("cliente_documento" , "Seleccione un cliente");
      return;
    }

    let data = {
      codigo: $("[name=ref_serie]").val(),
      tipo_documento : $("[name=ref_documento]").val(),
    };
    let funcs = {
      success : serie_documento_ok,
      error : serie_documento_error,
    };

    ajaxs( data , url_verificar_serie , funcs);
  }

  function verifyInputCliente(){
    return $("[name=cliente_documento]").val().length;
  }

  function verifiy_tipo_documento()
  {
    if( verifyInputCliente() ){
      show_modal("show", modal_tipodocumento);
    }    
    else {
      notiYFocus("cliente_documento" , "Seleccione un cliente");
    }
  }

  function agregar_dias()
  {
    let dias = Number($("[name=forma_pago] option:selected").attr('data-dias'));    
    // let fecha = $("[name=fecha_referencia]").val();
    let fecha_inicial = $("[name=fecha_emision]").attr("data-fecha_inicial");

    if( dias == 0 ){
      $("[name=fecha_referencia]").val(fecha_inicial);
      return;
    }

    else {
      let d = new Date(new Date(fecha_inicial).setDate(dias));
      $("[name=fecha_referencia]").datepicker("update" , d );
    }

    console.info("sumando dias", dias, fecha_inicial );      
  }

  function date(){  

    $('.datepicker').datepicker({
      autoclose: true,
      language: 'es',
    });

  }


  function validateNumber(num , inputName = false)
  {
    let number = inputName ? $("[name=" + inputName + "]").val() : num;
    return ( number.length != 0 && reg_number.test(number) );
  }

  function validateIsNotNumber(param1,param2 = false){
    return !validateNumber(param1,param2);
  }


  function validateDocumentoRef()
  {
    if( validateIsNotNumber("","ref_documento") ){
      notiYFocus("tipo_cambio" , "Es obligatorio poner el tipo de documento, cuando es nota de credito");
    }

    else if( ! $( "[name=ref_documento]" ).val().length ){
      notiYFocus("tipo_cambio" , "Es obligatorio poner el tipo de documento, cuando es nota de credito");
    }  
  }

  function notiYFocus( inputFocus , notificacionMensaje , notificacionTipo = "error" )
  {
    $("[name=" + inputFocus + "]").focus();    
    notificaciones( notificacionMensaje, notificacionTipo );
  }

  function validarItem()
  {    
    let resp = true;

    if( validateIsNotNumber("" , "producto_codigo") ){
      notiYFocus( "producto_codigo" , "Ponga el codigo del producto" )
      resp = false;
    }

    else if( ! $("[name=producto_nombre]").val().length ){
      notiYFocus( "producto_nombre" , "No puede dejar vacia la descripción del producto");
      resp = false;
    }

    else if( validateIsNotNumber( "" ,  "producto_cantidad") ){
      notiYFocus( "producto_cantidad" , "La cantidad del producto tiene que ser un numero");
      resp = false;
    }

    else if( validateIsNotNumber( "" ,  "producto_precio") ){
      notiYFocus( "producto_precio" , "El precio del producto tiene que ser un numero");
      resp = false;
    }

    else if( validateIsNotNumber( "" ,  "producto_dct") ){
      notiYFocus( "producto_dct" , "El descuento del producto tiene que ser un numero");
      resp = false;
    }
    
    if( validateIsNotNumber( "" ,  "producto_importe") ){
      notiYFocus( "producto_importe" , "El importe de la compra no puede estar vacia");
      resp = false;
    }

    return resp;  
  }

  function verificarExistencia(){

  }

  function agregar_item()
  {
    console.log("accion para este item" , action_i() );
    if( executing_ajax ){
      return;
    }
    // No se estan creando esta factura por lo tanto no ay que crear ni editar creando por lo tanto no ay que agregar nada    
    if(! create ){
      console.log("no se esta creando nada")
      return;
    }

    if(! validarItem() ){
      console.log("No paso validación los campos del formulario");
      return;
    }

    // stock
    let stock = Number($("[name=producto_stock]").val());
    let cantidad = Number($("[name=producto_cantidad]").val());
    let precio = Number($("[name=producto_precio]").val());    
    
    if( cantidad > stock  ){      
     if(!confirm("Stock disponible es menor que la cantidad requerida, desea continuar?")){
      console.log("no desea continuar");
      $("[name=producto_cantidad]").focus().select();
      return;
     }
    }

    let info;

    // console.log("producto", current_product_data.producto );
    console.log("action_i()",action_i());
    if( action_i() == "create" ){
      info = { 
        Unidades : current_product_data.unidades,
        Marca : current_product_data.marca.MarNomb,
        MarCodi : current_product_data.marca.MarCodi,
        TieCodi : current_product_data.producto.tiecodi,
        DetPeso : current_product_data.producto.ProPeso,
      };
    }

    else {
      let trInfo = $("tr.seleccionando").data();
      info = { 
        Unidades : trInfo.unidades,
        Marca    : trInfo.info.Marca,
        MarCodi  : trInfo.info.MarCodi,
        TieCodi  : trInfo.info.TieCodi,
        DetPeso  : trInfo.info.ProPeso,
      };
    };



    info.DetCome = $("[name=commentario]").val();
    info.UniCodi = $("[name=producto_codigo]").val();
    info.DetNomb = $("[name=producto_nombre]").val();
    info.DetUni  = $("[name=producto_unidad]").val();
    info.DetUniNomb = $("[name=producto_unidad] option:selected").text();
    info.DetCant = $("[name=producto_cantidad]").val();      
    info.DetPrec = $("[name=producto_precio]").val();
    info.DetDcto = $("[name=producto_dct]").val();
    info.DetPercP = $("[name=producto_percepcion]").val();    
    info.DetPercV = $("[name=producto_percepcion_importe]").val();        
    info.DetBase = $("[name=producto_igv] option:selected").val();
    info.DetIGVP = $("[data-namedb=proigvv]").val();
    info.DetIGVV = $("[name=producto_igv_total]").val();
    info.DetISC  = $("[name=producto_isc]").val();
    info.DetISP  = $("[name=producto_isc_other]").val();
    info.DetImpo  = $("[name=producto_importe]").val();

    let success_func = action_i() == "create" ? add_item : edit_item;

    let funcs = {
      success: success_func,
    }
    ajaxs( info , url_verificar_item_info, funcs );        

    console.log("informacion a mandar", info );

  }

  function error_item(){
    console.log("ay un error en el item");
  }

  function modificar_tr(info, tr ){
    // console.log("tr", tr);
    for( prop in info ){
      //
      // console.log(tr.find("[data-campo=" + prop + "]") , prop );
      tr.find("[data-campo=" + prop + "]").text( info[prop] );
    }
  }


  function edit_item(info)
  {
    notificaciones("Item modificado exitosamente", "success");
    let tr = $("tr.seleccionando");

    tr.hide(500, function(){      
      modificar_tr(info,tr);
      tr
      .show(500)
      .removeClass("seleccionando");
    });

    console.log("editando item" , info);    
    tr.attr('data-info', JSON.stringify(info));
    cleanInputsGroup("producto" , quitar_unidad );
    action_item = "create";    
    poner_totales_cant();    
  }

  function add_item(info)
  {
    let tbody = table_items.find("tbody");

    let trItem = $("<tr></td>")
    .addClass('tr_item')
    .attr({ 
      'data-info' : JSON.stringify(info), 
      'data-unidades' : JSON.stringify(current_product_data.unidades)
    });

    let itemNume = table_items.find("tbody tr").length + 1;
    
    // Elementos 
    let tdBS = tdCreate( info.TieCodi , false , "TieCodi");
    let tdGra = tdCreate( info.DetBase , false , "DetBase" );
    let tdItem = tdCreate( itemNume, false , "itemNum");
    let tdCod = tdCreate( info.UniCodi , false , "UniCodi");
    let tdUni = tdCreate( info.DetUniNomb , false , "DetUniNomb");
    let tdDes = tdCreate( info.DetNomb, false  , "DetNom");
    let tdMarca = tdCreate(info.Marca ,false , "Marc");
    let tdCant = tdCreate( info.DetCant , false , "DetCant");
    let tdDcto = tdCreate( info.DetDcto , false , "DetDcto");
    let tdISC = tdCreate( info.DetISC , false , "DetISC");
    let tdIGP = tdCreate( info.DetIGVP , false , "DetIGVV");
    let tdPrecio = tdCreate( info.DetPrec ,false , "DetPrec");
    let tdImporte = tdCreate( info.DetImpo , false , "DetImpo");

    trItem.append(tdBS,tdGra,tdItem,tdCod,tdUni,tdDes,tdMarca,tdCant,tdPrecio,tdDcto,tdIGP,tdISC, tdImporte);

    tbody.prepend(trItem);

    let total = Number( $("[name=producto_importe]").val() );
    let total_importe = total + Number(info.DetPrec);
    // console.log("total y total_importe", total , total_importe);
    $("[name=total_importe]").val(total);

    cleanInputsGroup("producto" , quitar_unidad );
    $("[name=producto_nombre]").focus();

    habilitarDesactivarSelect( "tipo_cambio" , false );    
    habilitarDesactivarSelect( "moneda" , false );

    poner_totales_cant();
    descuento_global();
  }

  function habilitarDesactivarSelect( select_name , activar = true )
  {
    let select = $("[name=" +  select_name + "]");

    if( select.length ){
      activar ? select.removeAttr('disabled') : select.attr('disabled','disabled');
    }
    else {
      // console.log("activar elemento");
      activar ? $("." + select_name).removeClass('disabled') : $("." + select_name ).addClass('disabled')
    }
  }

  function verificar_producto_unidad( fromTable = true ){
    if( fromTable ){
      $("[name=producto_unidad]").focus();
    }
  }

  function check_tipo_documento()
  {
    var tipo_documento = $("[name=tipo_documento] option:selected");
    if( $.trim(tipo_documento.text().toLowerCase()) === "nota de credito" ){      
      $("[name=ref_documento]").focus();      
    }    
    else {
      $("[name=producto_nombre]").focus();      
    }
  }

  function defaultErrorAjaxFunc(data){
    console.log( "error ajax" , data.responseJSON );
    let errors = data.responseJSON.errors;
    let mensaje = data.responseJSON.message;

    let erros_arr = [];
    // console.log("errors",errors);
    for( prop in errors ){
      for( let i = 0; i < errors[prop].length; i++  ){
        erros_arr.push( errors[prop][i] );
        // let form  = $( form_accion );
        // poner_error( $( form_accion + " input").filter("[name=" + prop  + "]") );
      }
    }
    console.log("error" , erros_arr , mensaje );
    // console.log("error del ajax" , data );
    notificaciones( erros_arr , 'error' , mensaje ); 
  }

  function ajaxs( data , url , funcs = {} ){
    $.ajax({
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
    });  
  };  

  function poner_codigo_documento(){
    let nro_codigo = $("[name=serie_documento] option:selected").attr('data-codigo');
    $("[name=nro_documento]").val( nro_codigo );    
  }


  function show_modal( action = "show" , modal = "#modalAccion" , backdrop = true )
  {   
    if( action == "hide" ){          
      $(modal).modal(action);      
    }
    else {
      // let options = {
      //   show : (action == "show"),
      // };

      // if( backdrop == "static" ){
      //   options.backdrop = backdrop;
      // };
      $(modal).modal({
        show: true,
        backdrop : backdrop
      });      
      
    }
  }


  function buscar_en_table( buscar, table = table_clientes )
  {
    table.search(buscar).draw();
  }

  function findInput(inputName){
    return $("[name=" + inputName + "]")    
  }

  function buscar_cliente(){    

    let nro_documento = findInput("cliente_documento").val();
    buscar_en_table( nro_documento,  table_clientes );
    show_modal("show", "#modalSelectCliente");
    
    /*

      $('#datatable-clientes_filter input[type=search]')[0].focus();

    */
  }

  function select_first_ele(table){
    
    if( !table.find("tbody tr").find(".dataTables_empty").length ){
      table.find("tbody tr").eq(0).addClass('select');
    }
  }


  function select_tabla_productos()
  {
    select_first_ele( $("#datatable-productos") );
  }

  function select_tabla_clientes()
  {
    select_first_ele( $("#datatable-clientes") );
  }

  function select_tabla_tipodocumento()
  {
    select_first_ele( $("#datatable-tipopago_select") );
  }


  function price_moneda(data)
  {
    console.log("poniendo precio a moneda")
  }

  function seleccionando_ele_table(tr)
  {
    let table = tr.parents("table");
    let trSelect = $("tr.select" , table );    

    if( trSelect.length ){

      var allTrTable = trSelect.parents("table").find("tbody tr").toArray();
      if( key == 40 ){
        if( trSelect.index() != allTrTable.length-1){    
          trSelect.removeClass("select");
          $( allTrTable[ $(trSelect).index() + 1 ]).addClass("select");           
        }
      }

      else {
        if( trSelect.index() ){
          trSelect.removeClass("select");          
          $(allTrTable[$(trSelect).index()-1]).addClass("select");
        }        
      }
    }
  }

  function subir_bajar_table( key , modal )
  {
    let trSelect = $("table tr.select" , modal );    
    if( trSelect.length ){
      var allTrTable = trSelect.parents("table").find("tbody tr").toArray();
      if( key == 40 ){
        if( trSelect.index() != allTrTable.length-1){    
          trSelect.removeClass("select");
          $( allTrTable[ $(trSelect).index() + 1 ]).addClass("select");           
        }
      }
      else {
        if( trSelect.index() ){
          trSelect.removeClass("select");          
          $(allTrTable[$(trSelect).index()-1]).addClass("select");
        }        
      }
    }
  }

  function poner_data_inputs( data , no_adicional = true , adicional = null , name_busqueda = "data-namedb" ){  

    for( prop in data ){  

      if( no_adicional !== true && no_adicional[prop]  ){
        no_adicional[prop]( data , adicional );
      }

      else  {
        let ele = $("["+name_busqueda+"="+prop+"]");        
        $("[" + name_busqueda + "=" + prop + "]").val( data[prop] );
      }

    }
  }

  function agregarASelect( data, select, name_codi , name_text , adicional_info = [] )
  {
    let select_agregar = $( "[name=" + select + "]");    
        select_agregar.empty();

    for( let i = 0; i < data.length ; i++ ){
      let actual_data = data[i];

      let option = $("<option></option>")
      .attr('value' , actual_data[name_codi] )
      .text( actual_data[name_text] );

      if( adicional_info.length ){
        for( let i = 0; i < adicional_info.length; i++ ){
          let info = adicional_info[i];
          option.attr( info[0] , actual_data[info[1]] );          
        }
      }
      select_agregar.append(option);
    }
  }


  function is_nota_credito()
  {
    return $("[name=tipo_documento] option:selected").text().trim() === "NOTA DE CREDITO";
  }

  function cambiar_tipo_documento()
  {
    let tipo_documento = $("[name=tipo_documento] option:selected");    
    let data = eval( tipo_documento.attr('data-series'));
    let optionDefault = tipo_documento.attr('data-series');
    let div_datos = $(".div_datos_referenciales");

    agregarASelect( 
      data,
      'serie_documento',
      'nombre',
      'id',
      [['data-codigo','nuevo_codigo']],
      );

    console.log( "nota de credito" , is_nota_credito );

    if( is_nota_credito() ){      
      $(".group_ref").removeAttr('disabled','disabled');
      div_datos.removeClass('block');
    }
    else {
      div_datos.addClass('block');
      $(".group_ref")
      .attr('disabled','disabled')
      .val('');
    }

    poner_codigo_documento();    
  }

  function poner_data_cliente(data)
  {
    poner_data_inputs(data);
    show_modal( "hide" , modal_cliente );
    nextFocus("cliente_documento");
  }

  function poner_unidades(data)
  {
    let unidades = data.unidades;        
    console.log( "unidades" , unidades );
    $("[name=producto_unidad]").empty();

    for( let i = 0; i < unidades.length; i++ ){
      let option = $("<option></option>")
      .attr('value' , unidades[i].Unicodi )
      .text( unidades[i].UniAbre);

    $("[name=producto_unidad]").append(option)
   };   
  }

  function set_precio()
  {
    // console.log("cambio precio unidades");
    let codigo_moneda, unidades;
    console.log("action_i()" , action_i()  )
    if( action_i() == "create"  ){
      codigo_moneda = current_product_data.producto.moncodi;
      unidades = current_product_data.unidades;
    }
    else {
      codigo_moneda = current_product_data.producto.moncodi;
      unidades = current_product_data.unidades;      
    }

    // console.log( "codigo_moneda" , codigo_moneda );
    let precio;
    let moneda = $("[name=moneda] option[value=" + codigo_moneda + "]").text();
    let producto_unidad = $("[name=producto_unidad] option:selected");
    let unidad_select = null;

    let is_sol = Number($("[name=moneda] option:selected").attr('data-esSol'));    

    for( let i = 0; i < unidades.length; i++ ){
      if( unidades[i].UniAbre == producto_unidad.text() ){
        unidad_select = unidades[i];
        break;
      }
    }

    precio = is_sol ? unidad_select.UNIPUVS : unidad_select.UniPUVD;

    $("[name=producto_precio]").val( precio );
    calcular_importe();    
  }

  function dcto_defecto(data){
    if( data.ProDct1 === null || data.ProDct1 === "" ){
      $("[name=producto_dct]").val(0);
    }

  }

  function poner_data_producto(data)
  {
    show_modal( "hide" , "#modalSelectProducto");        
    data.producto.ProUnidades = null;
    $("[name=producto_cantidad]").val(1);
    let funcs_agregar = {
     "ProUnidades" : poner_unidades,
     "ProDcto1" : dcto_defecto,     
    };            
    current_product_data = data;
    poner_data_inputs(data.producto, funcs_agregar);    
    nextFocus("producto_nombre");
    set_precio();
  }

  function tipodocumento_selected(data)
  {
    console.log("tiopo documento select");
    $("[name=ref_documento]").val( data.TidCodi );
    $("[name=ref_serie]").focus();
    show_modal("hide" , modal_tipodocumento);    
  }

  function enter_table_ele_click(){

    let modal = $(".modal.fade.in");
    enter_table_ele(modal)
  }

  function enter_table_ele( modal )
  {
    let trSelect = $("table tr.select" , modal );

    if( trSelect.length ){

     if( modal.is("#modalSelectCliente") ){
        let data = {
          codigo : trSelect.find("td:eq(0)").text(),
          tipo_documento : trSelect.find("td:eq(1)").text(),
        };
        let funcs = {
          success: poner_data_cliente,
        };
        ajaxs( data , url_buscar_cliente , funcs  );
      }

      else if( modal.is("#modalNuevoCliente") ){
        document.getElementById("nuevocliente").focus();
      }

      else if( modal.is("#modalSelectProducto")){
        
        let data = {
          codigo : trSelect.find("td:eq(0)").text(),
        };        
        let funcs = {
          success: poner_data_producto,
        };
        ajaxs( data , url_buscar_producto_datos , funcs  );        
      }

      else if( modal.is("#modalSelectTipoDocumentoPago")) {        
        // console.log("dandole enter en el modal de tipo de documento pago");
        let data = {
          codigo : trSelect.find("td:eq(0)").text(),
        };        
        let funcs = {
          success: tipodocumento_selected,
        };
        ajaxs( data , url_buscar_tipo_documento , funcs  );        
      }

    }    
  }

  function nextFocus(elemento, e = false)
  {
    let nextInputNameOrFunc = focus_orden[elemento];        
    if( typeof nextInputNameOrFunc == "string" ){
      $("[name=" + nextInputNameOrFunc + "]")
      .focus()
      .select();      
    }
    else {
      if( name == 'producto_cantidad' || name == 'producto_precio' || name == 'producto_dct'){
        // return false;
        console.log("no ejecutar el codigo");
      }
      nextInputNameOrFunc();
    }
  }

  function setClienteDataAndNextInput(data)
  {
    poner_data_inputs(data);
    nextFocus( "cliente_documento" );
  }

  function quitar_unidad(){
    $("[name=producto_unidad]").empty();
  }

  function cleanInputsGroup( grupo = 'cliente' , other_code = false )
  {
    let grupo_inputs = {
      cliente : $(".inputs_cliente"),
      producto : $(".inputs_producto"),
    }

    grupo_inputs[grupo].val("");
    setDefaultOptions(grupo_inputs[grupo]);
    other_code ? other_code() : null;
  }

  function setDefaultOptions(ele){
    ele.each(function(){
      if( $(this).is("[data-default]") ){
        let default_value = $(this).data('default');
          this.nodeName == "INPUT" ?
          $(this).val(default_value) : 
          $(this).text(default_value);
      }
    })
  }


  function activateOrDesactivateSection(seccion)
  {
    let secc = {
      calculadora : 'banco',
      banco : 'calculadora',      
    };

    let div_seccion = $("." + seccion);
    let div_seccion_opuesta = $( "." + secc[seccion]);

    div_seccion
    .removeClass('inactive');

    div_seccion_opuesta
    .addClass('inactive')

    div_seccion.find("select,input").not('.disabledFijo').each(function(index,dom){
      console.log( "activar estos elementos", $(dom));
      $(dom).prop('disabled',false);      
    });

    div_seccion_opuesta.find("select,input").not('.disabledFijo').each(function(index,dom){
      $(dom).prop('disabled',true);
    });    
  }

  function error_buscando_cliente(data)
  {    
    cleanInputsGroup();
    show_modal( "show" , "#modalNuevoCliente")    
    document.getElementById("nuevocliente").focus();
    document.getElementById("nuevocliente").focus();
  } 

  function accionar_buscar_cliente(e)
  {
    let cliente_documento = $("[name=cliente_documento]").val();  

    if( e.keyCode === 13 ){

      if( cliente_documento.trim().length ){
        if( ! isNaN(Number(cliente_documento)) || cliente_documento == "." ){        

          let data = {
            codigo : cliente_documento,              
          };

          let funcs = {
            success : setClienteDataAndNextInput,
            error   : error_buscando_cliente
          };

          ajaxs( data , url_verificar_cliente , funcs );
        }
      }

      else {
        cleanInputsGroup();
      } 
    }
  }


  function calcular_porcentaje( e = false ){
    console.log("calculando porcentaje" , e );
    if(e){
      if( e.keyCode === 13 ) return false;      
    }

    console.log("calculando porcentaje");
    let porcentaje = Number($("[name=producto_percepcion]").val());    
    let producto_percepcion = $("[name=producto_percepcion_importe]");
    let value_importe = Number($("[name=producto_importe]").val());
    let total = 0;

    producto_percepcion.val("0");

    if( validateNumber(value_importe) && validateNumber(porcentaje) ){      
      total = fixedValue( (value_importe / 100) * porcentaje)
      producto_percepcion.val(total);
    }
    return total;
  }

  function calcular_igv()
  {
    let igv = $("[name=producto_igv]");    
    let igv_total = $("[name=producto_igv_total]");
    let value_igv = Number(igv.find("option:selected").attr("data-porc"));
    let value_importe = Number($("[name=producto_importe]").val());
    
    $("[data-namedb=proigvv]").val(igv.find("option:selected").data('value'));

    if( validateNumber(value_importe) ){

      let importe_resto = value_igv ?
        fixedValue( value_importe / value_igv) : value_igv;

      let resultado = importe_resto ? fixedValue(value_importe - importe_resto) : importe_resto;

      igv_total.val(resultado);
    }    

    else {
      igv_total.val("");
    }
  }

  // serch pro wraning
  function accionar_buscar_producto(e)
  {
    if( e.keyCode === 13 || e.keyCode === undefined ){
      let producto_name = 
      $(this).is('[name=producto_nombre]') ?
        $('[name=producto_nombre]').val() :
        $('[name=producto_codigo]').val();

      show_modal( "show" , "#modalSelectProducto");
      table_productos.search( producto_name ).draw();  
    }
    
    // e.stopPropagation();
  }

  function importe(){
    return fixedValue( 
      $("[name=producto_cantidad]").val() * $("[name=producto_precio]").val()
      ); 
  }

  function calcular_importe()
  {
    $("[name=producto_importe]").val( fixedValue(importe() - descuento()));
  }

  function fixedValue(value_importe){
    if(value_importe == undefined){
      return "0";
    }
    value = typeof value_importe == "string" ? Number(value_importe) : value_importe;
    return value.toFixed(2);
  }

  function descuento(){
    let descuento_value = Number($("[name=producto_dct]").val());
    return (importe() / 100) * descuento_value;
  }

  function calcular_descuento(e)
  {
    if(e){
      if( e.keyCode === 13 ) return false;
    } 
    let $importe = $("[name=producto_importe]");

    console.log("i,d", importe() , descuento() );

    $importe.val( fixedValue( importe() - descuento()) );
  }

  function moneda_precio_change()
  {
    set_precio();
  }

  function teclado_acciones(e)
  {
    // 40 => [hacia abajo]
    // 38 => [hacia arriba]
    // 30 => [enter]
    let keyCode =  e.keyCode;
    let modalUp = modales_select.filter('.in');
    let modalIsOpen = modales_select.is(':visible');

    // Subir o bajar
    if( keyCode === 40 || keyCode === 38 ){          
      if( modalIsOpen ){        
        subir_bajar_table( keyCode , modalUp );
        return false; // loekff_4458 
      }
    }

    // Enter
    else if( keyCode === 13 ){      
      if( modalIsOpen ){              
        enter_table_ele( modalUp );
        return false; // loekff_4458
      }
    }
  }

  function cambiar_focos(e)
  {
    if( e.keyCode == 13  ){
      let name_elemento = $(this).attr('name');
      console.log("le has dado enter al elemento" ,  name_elemento );
      nextFocus(name_elemento , e);      
    }
  }

  function action_i( action = false ){
    if( action ){
      action_item = action;
    }
    return action_item;
  }

  function select_item()
  {
    let $this = $(this);
    let tr_selec = $this.parents("tbody").find(".seleccionando");
    let data = null;
    cleanInputsGroup("producto");

    if( tr_selec.length ){
      // quitar selección
      if($this.is(tr_selec)){
        $this.removeClass('seleccionando');
        habilitarDesactivarSelect("eliminar_item", false );
        action_i("create");
      }
      //
      else {
        tr_selec.removeClass('seleccionando');        
        data = JSON.parse($this.attr('data-info'));
        $this.addClass("seleccionando");
      }
    }    

    else {
      $this.addClass('seleccionando');          
      data = JSON.parse($this.attr('data-info'));
    }
    // console.log("elemento ", $this);
    if( data ){
      action_i("edit");      
      current_product_data = data;
      console.log("data item", data);
      let unidades = { unidades : JSON.parse($this.attr('data-unidades'))  };
      poner_unidades(unidades);
      poner_data_inputs( data , true , null , 'data-name_item' );
      habilitarDesactivarSelect("eliminar_item", true );
    }
  }

  function eliminar_item(e)
  {
    e.preventDefault();
    $(".tr_item.seleccionando").hide(100, function(){
      $(".tr_item.seleccionando").remove();      
      poner_totales_cant();
    });
    action_i("create");
    cleanInputsGroup('producto' , quitar_unidad );        
    habilitarDesactivarSelect("eliminar_item", false);

    if(! $(".tr_item.seleccionando").length ){

    }
  }

  function show_comment_div(e)
  {
    let div_com = $(".div_comentario");    
    div_com.toggle();        

    if( div_com.is(':visible') ){
      div_com.find("textarea").focus();
    }

    div_com.css({ 
      'top' : e.screenY - 100,
      'left' : e.screenX,       
    });
  }

  function verificar_data_factura(){
    
    var tipo_documento = $("[name=tipo_documento] option:selected");    

    if( tipo_documento.text().toLowerCase().indexOf("boleta") == -1  ){
      if( ! verifyInputCliente() ){
        notiYFocus("cliente_documento", "Tiene que introducir el numero del documento del cliente");
        return;
      }      
    }

    if( $.trim(tipo_documento.text().toLowerCase()) === "nota de credito" ){      

      if( ! $("[name=ref_documento]").val().length ){
        notiYFocus("ref_documento", "Tiene que introducir el tipo de documento");
        return;
      }
      if( ! $("[name=ref_serie]").val().length ){
        notiYFocus("ref_serie", "Tiene que introducir la serie del documento");
        return;
      }
      if( ! $("[name=ref_numero]").val().length ){
        notiYFocus("ref_numero", "Tiene que introducir el numero de documento");
        return;
      }  
      if( ! $("[name=ref_motivo]").val().length ){
        notiYFocus("ref_motivo", "Tiene que ingresar el motivo");
        return;
      }
    }    

    if( ! $("#table-items tbody tr").length ){
      notiYFocus("producto_nombre", "Tiene que introducir al menos un producto");
      return;
    }  

    let data = {
      'id_cliente' : $("[data-namedb=PCRucc]").val()
    };

    let funcs = {
      success : agregar_deudas_info
    };

    ajaxs( data , url_guia_salida , funcs );
  }


  function guardar_factura(data)
  {
    console.log("items guardados" , data );
    create = 0;
    show_modal("hide" , "#modalGuardarFactura");        
    $("#guardarFactura").addClass('disabled');        
    if( tipo_guardado == "print" ){
      window.open( data.url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
    }    
    pago_factura();
    let dta = {
      id_factura :  $("[name=codigo_venta]").val()
    };
    let funcs = {
      success : procesar_pago,
    };
    ajaxs( data , url_pago , funcs );
    show_modal("hide" , "#modalGuardarFactura");
    show_modal("show" , "#modalPago" , "static");
  }


  function error_guardar_factura(data)
  {
    defaultErrorAjaxFunc(data);    
  }

  function modal_guardar()
  {
    show_modal("show", "#modalGuardarFactura" , "static")
  }

  function aceptar_guardado()
  {    
    let items = [];

    $("#table-items .tr_item").each(function(i,d){      
      let info = JSON.parse($(this).attr('data-info'));
      items.push(info);
    });

    console.log("items",items);
    
    let data = {
      codigo_venta : $("[name=codigo_venta]").val(),
      tipo_documento : $("[name=tipo_documento]").val(),
      serie_documento : $("[name=serie_documento]").val(),
      nro_documento : $("[name=nro_documento]").val(),
      cliente_documento : $("[name=cliente_documento]").val(),
      cliente_nombre : $("[name=cliente_nombre]").val(),
      moneda : $("[name=moneda]").val(),  
      tipo_cambio : $("[name=tipo_cambio]").val(),                
      forma_pago : $("[name=forma_pago]").val(),
      fecha_emision : $("[name=fecha_emision]").val(),
      fecha_vencimiento : $("[name=fecha_referencia]").val(),
      Vtabase : $('[data-name=gravadas]').val(),
      VtaDcto : $('[data-name=descuento]').val(),
      VtaInaf : $('[data-name=inafectas]').val(),
      VtaExon : $('[data-name=exoneradas]').val(),
      VtaGrat : $('[data-name=gratuitas]').val(),
      VtaISC  : $('[data-name=isc]').val(),
      VtaIGVV : $('[data-name=igv]').val(),
      total_cantidad : $("[name=cantidad_total]").val(),
      total_peso : $("[name=peso_total]").val(),
      total_importe : $("[name=total_importe]").val(),
      vendedor : $("[name=vendedor]").val(),
      nro_pedido : $("[name=nro_pedido]").val(),
      doc_ref : $("[name=doc_ref]").val(),
      ref_serie : $("[name=ref_serie]").val(),
      ref_numero : $("[name=ref_numero]").val(),
      ref_fecha : $("[name=ref_fecha]").val(),      
      ref_motivo : $("[name=ref_motivo]").val(),
      items : items,
    }

    $("tr.seleccionando").each(function(index,dom){      
      let item_data = JSON.parse($(this).attr('data-info'));
      console.log( "item data" , item_data);
      data.items.push(item_data);
    });

    let funcs = {
      success : guardar_factura,
      error : error_guardar_factura,
      complete : function(){
        $("#aceptar_guardado").removeClass('disabled');
      }
    }

    tipo_guardado = $("[name=tipo_guardado]:checked").val();
    // console.log("desabilitando boton")
    ajaxs( data , url_verificar_factura , funcs );

  }

  function show_hide_adicional_info()
  {
    $(".info_adicional").toggle();
  }

  function procesar_pago( data )
  {
    if(data.pago){
      poner_data_inputs( data.venta, true, null, "data-db" )
      poner_data_inputs( data, true, null, "data-db" )      
      show_modal("show" , "#modalPago" , "static");
      if( data.is_efectivo ){
        activateOrDesactivateSection( "calculadora");
      }
      else {
        activateOrDesactivateSection("banco");        
      }
    }

    else {
      console.log("data del pago", data);
      guiaSalida(data);
    }
  }

  function guiaSalida(data)
  { 
    console.log("data en la guia de salida" , data);
    poner_data_inputs( data, true, null, "data-de");
    show_modal("hide", "#modalPago");
    show_modal("show", "#modalGuiaSalida","static");    
  }


  function pago_factura()
  {
    let data = {
      id_factura : $("[name=codigo_venta]").val(),
    };

    let funcs = {
      success : procesar_pago,
    }; 

    console.log("pagar factura",data);
    ajaxs( data, url_check_pago , funcs);
  }

  function desactivarArea()
  {
    let option = $("[data-name=TipPago] option:selected");    
    console.log( option.data('is_efectivo') );

    option.data('is_efectivo') ? activateOrDesactivateSection("calculadora") : activateOrDesactivateSection("banco");
  }

  function poner_cuentas_banco()
  {
    let option = $("[data-db=BanCodi] option:selected");    
    let data = option.data('cuentas');
    let data2 = option.attr('data-cuentas');    
    console.log("cuentas de este banco", data, data2);
    agregarASelect( data , "CuenCodi" , "CueCodi" , "CueNume");
    cambiarNumOper();
  }

  function cambiarNumOper()
  {
     let banco = $("[data-db=BanCodi]").val();
     let cuenta = $("[data-db=CuenCodi]").val();
     let f = new Date();     
     let year = f.getFullYear();
     let m = f.getMonth() + 1;
     let mes = (m < 10) ? "0"+m : m;
     let nuevo_nroperacion = banco.concat(banco,cuenta,year,mes);
     console.log("nuevo numero de operación", nuevo_nroperacion);
    $("[data-db=NumOper]").val(nuevo_nroperacion);
  }


  function calculadora_resultado(cantidad, importe){

    let estado_pago = $("#estado_pago");

    console.log("cantidad, importe" , cantidad, importe );

    estado_pago.removeAttr('class');    
    if( cantidad > importe ){
      estado_pago
      .addClass('VUELTO')
      .text("VUELTO");
    }
    else if( cantidad < importe ){
      estado_pago
      .addClass('SALDO')
      .text("SALDO");      
    }    
    else {
      estado_pago
      .addClass('COMPLETE')
      .text("COMPLETE");      
    }    
  }

  function calculadora_actions()
  {
    console.log("calculadora actions");
    let soles = $( "[name=soles]" , ".calculadora");
    let soles_value = Number(soles.val());

    let dolar = $("[name=dolar]" , ".calculadora");
    let dolar_value = Number(dolar.val());

    let t_cambio = $("[name=VtaTcam]" , ".calculadora");    
    let t_cambio_value = Number(t_cambio.val());    

    let t_recibido = $("[name=totalRecibido]",".calculadora");        
    let t_recibido_value = Number(t_recibido.val());        

    let t_operacion = $("[name=totalOperacion]");
    let t_operacion_value = Number(t_operacion.val());

    let importe = Number($("[data-db=VtaImpo]").val());    

    if(validateIsNotNumber(soles_value) || validateIsNotNumber(dolar_value)  || 
      validateIsNotNumber(t_cambio_value)){
      t_recibido.val(0);
      t_operacion.val(0)
      return;
    }
    else {
      let dolar_operacion = (dolar_value * t_cambio_value);
      let total = soles_value + dolar_operacion;

      t_recibido.val(total);
      t_operacion.val(importe-total);

      calculadora_resultado(total,importe);
    }

  }

  function pagar_factura()
  {

    let importe = $("[name=VtaImpo]").val()

    console.log( "pagar factura", importe );
    if( validateIsNotNumber(importe) ){
      notiYFocus("VtaImpo" , "El importe tiene que ser un numero")
      return;
    }

    console.log($(".banco").is('inactive'));

    if( !$(".banco").is('inactive') ){
      
      if( $("[name=fechaPago]").val().length == 0 ){
      notiYFocus("fechaPago" , "La fecha de pago es necesaria")
      return;
      }
      if( $("[name=fechaVen]").val().length == 0 ){
      notiYFocus("fechaVen" , "La fecha vencimiento es necesaria")
      return;
      }      
    } 
    
    let data = {
      PagOper : $("[data-db=PagOper]").val(),
      VtaOper : $("[data-db=VtaOper]").val(),
      VtaImpo : $("[name=imp]").val(),
      BanCodi : $("[name=BanCodi]").val(),
      TpgCodi : $("[data-name=TipPago]").val(),
      VtaFVta : $("[data-db=fechaPago]").val(),
      VtaFVen : $("[data-db=fechaVen]").val(),
      VtaNume : $("[data-db=VtaNume]").val(),
    };

    console.log( "data" , data );
    let funcs = {
      success : guiaSalida,
    };

    ajaxs( data , url_save_pago, funcs );
  }


  function salir_guia()
  {
    show_modal("hide", "#modalGuiaSalida");
    go_listado()
  }

  function go_listado()
  {
    // return;
    window.location.href = url_listado_pago;
  }

  function guardar_guia_salida()
  {
    if(confirm("Esta seguro que desea confirmar la guia de salida")){

      let funcs = {
        success : go_listado
      };
      let data = {
        'id_almacen' : $("[name=almacen_id]").val(),
        'id_movimiento' : $("[name=tipo_movimiento]").val(),        
        'id_factura' : $("[name=codigo_venta]").val(),
      };

      ajaxs( data , url_save_guiasalida , funcs  )

      console.log("aceptar guia de salida");
    }

    else {
      go_listado();
      console.log("no tener una guia de salida");
    }
  }


  function headerAjax(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });   
  }  

  function initialFocus(){
    $("[name=producto_nombre]").focus();
  }

  // function initTooltip(){
  //   // $("[data-toggle=tooltip]").tooltip();
  // }

  function seleccionar_elemento( tr )
  {
    let table = tr.parents("table");  
    let tr_select = table.find("tr.select");        

    if( tr_select.length ){
      tr_select.removeClass("select");
    }

    tr.addClass("select");
  }

  // enter_table_ele


  function seleccion_elemento(){
    
    let tr = $(this);
    if( tr.find(".dataTables_empty").length == 0 ){
      seleccionar_elemento(tr);
    }
 
  }

  function salir_alert(){

    if( confirm("Esta seguro de salir") ){
      go_listado();
    }

  }  

  function accion_item(e)
  {
    e.preventDefault();

    let $t = $(this);
    console.log("$t" , $t);
    
    if( $t.is('.crear')  ){
      agregar_item();
    }

    else if( $t.is('.modificar')  ){
    }
    else {
      console.log("eli")
    }    

  }



  function calculate_dcto_global(){
   let resp = 0;
   let dcto_gl = $("[name=descuento_global]").val();
   let totales = sum_cant();

   if( validateNumber(dcto_gl) && $("[data-name=total_importe]").val() != "0" ){
    
    let total_venta = Number(totales.gravadas) + Number(totales.exoneradas) + Number(totales.inafectas);

    console.log("total venta, descuento y dcto valor", total_venta , (total_venta / 100 * dcto_gl) );

    return (total_venta / 100 * dcto_gl)

   }

   return resp;
  }

  function calculate_descuento( value , dcto )
  {  
  let elementos = [
    $("[data-name=descuento]"),
    $("[data-name=gravadas]"),
    $("[data-name=inafectas]"),
    $("[data-name=exoneradas]"),
    $("[data-name=igv]"),
    $("[data-name=total_documento]"),
    $("[data-name=total_importe]"),
    ];

    for (var i = 0; i < elementos.length; i++) {

      let ele = elementos[i];
      let eleValue = Number(ele.val());
      let res; 

      if(ele.is('[data-name=descuento]')){
        console.log("poner el descuento " , eleValue , value );
        res = eleValue + value;
      }
      else {
        res = eleValue ? eleValue - ((eleValue / 100) * dcto) : "0.00";
      }

      console.log("res desc", res);

      ele.val( fixedValue(res) );
    }

  }

  function descuento_global(e)
  {
    let valid_number = false;
    let $t = $("[name=descuento_global]");
    let value = $t.val();
    poner_totales_cant();

    if( validateNumber(value) && value != "0" ){

      let descuento = calculate_dcto_global();
      calculate_descuento( descuento , value );
    }
  }

  function events()
  {
    $("[name=descuento_global]").on('keyup' , descuento_global )

    $("[name=producto_dct]").on('keyup' , calcular_descuento)

    $(".buscar_cliente").on('click' , buscar_cliente );    

    $("#salir_").on('click' , salir_alert )

    $(".item-accion").on('click' , accion_item );


    table_clientes.on('draw.dt', select_tabla_clientes);
    table_productos.on('draw.dt', select_tabla_productos);
    
    // table_tipodocumento.on('draw.dt', select_tabla_tipodocumento);

    $("#datatable-productos,#datatable-clientes").on('click' , "tbody tr" , seleccion_elemento );

    $("#datatable-productos,#datatable-clientes").on('dblclick' , "tbody tr" , enter_table_ele_click );

    $("#pay_factura").on( 'click', pagar_factura );

    $(".elegir_elemento").on('click' , enter_table_ele_click  )

    $("*").on("keydown" , teclado_acciones );

    $("[name=cliente_documento]").on("keydown" , accionar_buscar_cliente );

    $("[name=producto_codigo] , [name=producto_nombre]").on("keydown" , accionar_buscar_producto );

    $("#boton_buscar").on("click" , accionar_buscar_producto );

    $("[name=producto_nombre]").on("dblclick" , accionar_buscar_producto );

    // cambiar de foco carefull
    $("[name=fecha_emision],[name=fecha_referencia],[name=moneda],[name=tipo_cambio],[name=forma_pago],[name=producto_unidad],[name=producto_cantidad],[name=producto_precio],[name=ref_documento],[name=ref_serie],[name=ref_numero],[name=ref_fecha], [name=producto_isc],[name=producto_isc_other],[name=producto_percepcion],[name=producto_dct] ").on("keydown", cambiar_focos );
   
    $("[name=soles],[name=dolar],[name=VtaTcam]" , ".calculadora").on( 'keyup', calculadora_actions );

    $(".aceptar_guia").on('click' , guardar_guia_salida );

    $("[data-db=BanCodi]").on( 'change', poner_cuentas_banco );

    $("[data-db=CuenCodi]").on( 'change', cambiarNumOper );

    $("[data-name=TipPago]").on('change' , desactivarArea );

    $(".pago").on('click' , pago_factura );

    $("#modalDeudas").on('hidden.bs.modal' , modal_guardar )

    $(".totales .info_principal").on('click' , show_hide_adicional_info );

    $("#guardarFactura").on('click' , verificar_data_factura );

    $("#aceptar_guardado").on('click' , aceptar_guardado );    

    $(".agregar_comentario").on('click' , show_comment_div );

    $(".eliminar_item").on('click' , eliminar_item );

    $("#table-items").on('dblclick' , ".tr_item" , select_item );

    $(".salir_guia").on('click' , salir_guia );

    $("[name=moneda]").on("change" , moneda_precio_change );

    $("[name=producto_unidad]").on("change" , set_precio );

    $("[name=producto_cantidad] , [name=producto_precio] , [name=producto_dct]").on("keyup" , calcular_importe );

    $("[name=producto_dct]").on("keyup" , calcular_descuento );   

    $("[name=producto_igv]").on("change" , calcular_igv );

    $("[name=forma_pago]").on("change" , agregar_dias );

    $("[name=producto_percepcion]").on("keyup" , calcular_porcentaje );

    // tipo de documento
    $("[name=tipo_documento]").on("change" , cambiar_tipo_documento );

    // serie documento
    $("[name=serie_documento]").on("change" , poner_codigo_documento );
  }

  function initDatatables()
  {    
    table_clientes = $('#datatable-clientes').DataTable({
      "processing" : true,
      "serverSide" : true,
      "lengthChange": false,
      "ordering": false,      
      "ajax": url_route_clientes_consulta,
      "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete" : function initComplete(settings, json){         
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      "columns" : [
        { data : 'PCCodi'  },
        { data : 'TipCodi' },       
        { data : 'PCRucc'  },
        { data : 'PCNomb'  },
      ]
    });

    table_productos = $('#datatable-productos').DataTable({
      "processing" : true,
      "serverSide" : true,
      "lengthChange": false,
      "ordering": false,  
      "ajax": url_route_productos_consulta,
      "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete" : function initComplete(settings, json){         
        $('div.dataTables_filter input').attr('placeholder', 'Buscar producto');
      },
      "columnDefs": [
        { "width": "10px", "targets": 0 },
        { "width": "40px", "targets": 1 },
        { "width": "100px","targets": 2 },
        { "width": "70px", "targets": 3 },
        { "width": "70px", "targets": 4 },
        { "width": "70px", "targets": 5 }
      ],
      "columns" : [
        { data : 'ProCodi' },
        { data : 'unpcodi' },
        { data : 'ProNomb' , className : 'nombre_producto' },
        { data : 'marcodi' , search : false },        
        { data : 'ProPUCD' , search : false },
        { data : 'ProPUCS' , search : false },
        { data : 'ProMarg' , search : false },
        { data : 'ProPUVS' , search : false },
        { data : 'ProPUVS' , search : false },        
        { data : 'prosto1' , search : false },
        { data : 'prosto2' , search : false },
        { data : 'prosto3' , search : false },
        { data : 'prosto4' , search : false },
        { data : 'prosto5' , search : false },
        { data : 'prosto6' , search : false },
        { data : 'prosto7' , search : false },
        { data : 'prosto8' , search : false },
        { data : 'ProPerc' , search : false },
        { data : 'ProPeso' , search : false },
        { data : 'BaseIGV' , search : false },
        { data : 'ISC'     , search : false },
        { data : 'tiecodi' , search : false },
      ]
    });

    table_productos.columns.adjust().draw();

  }


  function clearInputsInit()
  {
    if( create == "1" ){
      console.log("limpiando inputs")
      cleanInputsGroup('cliente');    
      cleanInputsGroup('producto');      
    }
  }

  function init()
  {

    $("[data-toggle=tooltip]").tooltip();
    headerAjax()
    initDatatables();
    events();
    initialFocus();
    clearInputsInit();
    poner_codigo_documento();
    date();
    console.log("datatables");
    $("#datatable-productos thead td").removeAttr("style");
  };

  // Ejecutar todas las funciones
  init();

});