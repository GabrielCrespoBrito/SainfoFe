function deleteSuccess(data)
{
  console.log( window.tr_selected );


  notificaciones( data.message , "success"); 
  window.tr_selected.hide(300 , function(){
    $(this).remove();
  })
  
}

function guardar_modificar_movimiento(e)
{
  let data = $("#form-movimiento").serialize();
  $("#load_screen").show();
  let funcs = {
    success : (data) => { 
      notificaciones("Se ha guardado exitosamente exitosamente",  "success"); 
      show_hide_modal("modalAccion", false );
      console.log("data",data);
      setTimeout(() => {
        window.location.reload();
      }, 500)
    }
  }

  let url = accion_modal() == "edit" ? url_modificar : url_crear;
  console.log("url" , url , accion_modal() , data );
  // e.preventDefault();
  ajaxs( data , url , funcs );
  return false;
}

function accion_modal(accion_cambio = false)
{
  return accion = accion_cambio ? accion_cambio : accion;
}

function modificar_caja()
{
  $("[name=id_movimiento]").val($("#table_movimiento tbody tr:eq(0)").find("td:eq(0)").text());
  $(".ingreso_soles").val( $("#table_movimiento tbody tr:eq(0)").find("td:eq(3)").text() );
  $(".ingreso_dolar").val( $("#table_movimiento tbody tr:eq(0)").find("td:eq(4)").text() );
  show_hide_modal("modalAccion", true );
}

function inputs_trabajar(data)
{
  console.log("inputs_trabajar" , data );

  $("[data-id]")
  .hide()
  .filter("[data-id=" + data.CtoCodi + "]")
  .show();

  if( data.CtoCodi == "004") {
    return data.MonCodi == "01" ? data.CANINGS : data.CANINGD;
  }

  else if( data.CtoCodi == "005"  ){    
    return data.MonCodi == "01" ? data.CANEGRS : data.CANENGD;
  }

}

function poner_data_modal(data)
{   
  console.log("poner_data_modal", data );
  $("#modalAccion .modal-title").text("Modificar");  
  poner_data_form(data, 'data-field' , { CANINGS : inputs_trabajar });
  show_hide_modal( "modalAccion", true )
}

function consultar_data()
{
  if( ay_seleccion()  ){
    cl("url_consultar", url_consultar );
    ajaxs({id_movimiento:$(".select td:eq(0)").text()} , url_consultar , { success : poner_data_modal })
  }
  else {
    notificaciones("Seleccione un elemento por favor" , "error")
  }

}

function ay_seleccion()
{ 
  let select = $("tr.select" , "#table_movimiento");
  if( select.length ){
    if( !select.find('.dataTables_empty').length ){
      return select;
    }  
  }

  return false;
}

function modificar_accion()
{
  if( tipo_movimiento == "003" ) {
    modificar_caja()
  }
  else {
    consultar_data()
    accion = "edit";
  }

}


function poner_enlace()
{
  let ver_boton = $("#ver");
  let seleccion = null;

  if( seleccion = ay_seleccion() ){
    ver_boton.attr('href', url_factura.replace('xxx' , seleccion.find("td:eq(2)").text() ) );
  }
  else {
    ver_boton.attr('href','#');
  }

}

function ir_enlace(e)
{ 
  if(! ay_seleccion() )
  {
    e.preventDefault();
    return false;
  }
}

function eliminar_seleccion(data)
{
  notificaciones("Borrado exitosamente", "success");
  ay_seleccion().hide(500 , function(){
    table.draw();    
    window.location.reload();
  });

}

function eliminar_movimiento()
{
  let seleccion = null;

  if (seleccion = ay_seleccion()) {

    if( confirm("Esta seguro de eliminar") ){
      ajaxs({ id_movimiento : seleccion.find("td:eq(0)").text() } , url_eliminar , { success : eliminar_seleccion });
    }
  }
}

function datatable_init()
{
  // console.log("datatable");
  window.table_movimientos = $('#table_movimiento').DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    createdRow: function createdRow(row, data, index) {
      var $row = $(row);
      $row.data('info', data);
    },
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra documento"
    },
    "serverSide": true,
    "ajax": {
      "url": url_search,
      "data": function (d) {
        return $.extend({}, d, {
        });
      }
    },
    "columns": [
      { data: 'Id' },
      { data: 'MocNume' },
      { data: 'documento' },
      { data: 'MOTIVO' },
      { data: 'MocNomb' },
      { data: 'monto_soles' },
      { data: 'monto_dolares' },
      { data: 'acciones' },
    ]
  });
}

function events()
{
  $("[name=tipos_movimientos]").on('change',function(){ window.location.href = this.value });
  
  $("#modificar").on('click' ,  modificar_accion );
  
  $("#guardar").on( 'click' , guardar_modificar_movimiento );
  $("#ver").on('click' ,  ir_enlace );
  $("#eliminar").on('click' ,  eliminar_movimiento );


  // console.log( "ingresos", window.Helper__ );
  // window.Helper__.init();

  // window.initSelect2();
  datatable_init();
  
  // $("[name=egreso_tipo]").on('change' ,  mostrar_adicional_inputs );  
  // $("#modalAccion").on('shown.bs.modal  ' ,  function(){$("[name=nombre]").focus()} );

  eventos_predeterminados( 'table_select_tr' , '#table_movimiento' , [ false , 'select' , poner_enlace ] );
  
  // eventos_predeterminados( 'table_datatable' , '#table_movimiento' , [{ paging: false,  search  : false , order: [[ 0, "desc" ]]}]);

  

  AppCajaEgreso.successCallbackDelete = deleteSuccess;
  

  AppCajaApertura.set_successCallback(function(data){
    let sol = $('.money_sol').text(data.soles);
    let dolar = $('.money_dolar').text(data.dolar);
  })


  $(".show-modalapertura").on('click'  , function(){
    let sol   = $('.money_sol').text();
    let dolar = $('.money_dolar').text();
    AppCajaApertura.show(sol,dolar);
  });

  // Modal ingreso

  $(".show-modalingreso").on('click'  , function(){
    console.log(AppCajaIngreso);
    AppCajaIngreso.create();
  });

  $("table").on( 'click' , '.show-ingreso' , function(){
    let data = $(this).parents('tr').data('info');
    console.log("show detalle" , data );
    AppCajaIngreso.show(data);
  });

  $("table").on( 'click' , '.edit-ingreso' , function(){
    let data = $(this).parents('tr').data('info');    
    AppCajaIngreso.edit(data);

  });

  // Modal ingreso

  $(".show-modalegreso").on('click'  , function(){
    console.log(AppCajaEgreso);
    AppCajaEgreso.create();
  });

  $("table").on( 'click' , '.show-egreso' , function(){
    let data = $(this).parents('tr').data('info');
    console.log("show detalle" , data );
    AppCajaEgreso.show(data);
  });

  $("table").on( 'click' , '.edit-egreso' , function(){
    let data = $(this).parents('tr').data('info');    
    AppCajaEgreso.edit(data);
  });

  $("table").on( 'click' , '.delete-egreso,.delete-ingreso' , function(e){    
    
    e.preventDefault();
    let url_delete = $(this).attr('href');    
    let id = $(this).parents('tr').find("td:eq(0)").text();   
    if( confirm("Esta seguro que desea borrar este registro")  ){
      window.tr_selected = $(this).parents('tr');
      AppCajaEgreso.delete( id , url_delete );
    }

  });

}

init(
  events,
  datepicker,
  ajaxs_setting.bind( null , {} , {defaultErrorAjaxFunc_showfield : true}),
)



