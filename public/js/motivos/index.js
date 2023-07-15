
function guardar_modificar_movimiento(e)
{
  let data = $("#form_accion").serialize();
  let funcs = {
    success : (data) => { 
      notificaciones("Se ha guardado exitosamente exitosamente",  "success"); 
      show_hide_modal("modalAccion", false );
      console.log("data",data);
      setTimeout( () => {
        window.location.reload();        
      }, 500)
    }
  }

  let url = accion_modal() == "edit" ? url_modificar : url_crear;


  console.log("url" , url , accion_modal() );
  // return;
  e.preventDefault();
  ajaxs( data , url , funcs );
  return false;
}

function accion_modal(accion_cambio = false)
{
  return accion = accion_cambio ? accion_cambio : accion;
}

function modificar_caja()
{
  $("[name=id_movimiento]").val( $("#table_movimiento tbody tr:eq(0)").find("td:eq(0)").text() );    
  $(".ingreso_soles").val( $("#table_movimiento tbody tr:eq(0)").find("td:eq(3)").text() );
  $(".ingreso_dolar").val( $("#table_movimiento tbody tr:eq(0)").find("td:eq(4)").text() );
  show_hide_modal("modalAccion", true );
}


function poner_data_modal(data)
{   
  $("#modalAccion .modal-title").text("Modificar");
  poner_data_form(data, 'data-field' , { 
    CANINGS : (data) => {return Number(data.CANINGD) > 0 ?  data.CANINGD : data.CANINGS }
  });
  show_hide_modal( "modalAccion", true )
}

function consultar_data()
{
  if( ay_seleccion()  ){
    ajaxs({id_movimiento:$(".select td:eq(0)").text()} , url_consultar , { success : poner_data_modal })
  }
  else {
    notificaciones("Seleccione un elemento por favor" , "error")
  }

}

function ay_seleccion()
{
  return $(".select").length ? $(".select") : false;
}

function modificar_accion()
{

  let seleccion = ay_seleccion();

  if( seleccion ){

    accion = "edit";

    let id = seleccion.find("td:eq(0)").text();
    let name = seleccion.find("td:eq(1)").text();

    $("#modalAccion .modal-title").text("Modificar");
    $("#modalAccion [name=name]").val(name);
    $("#modalAccion [name=id]").val(id);


    show_hide_modal( "modalAccion", true )

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
  if(! ay_seleccion() ){
    e.preventDefault();
    return false;
  }
}

function eliminar_seleccion(data)
{
  ay_seleccion().hide(500 , function(){    
    notificaciones("Borrado exitosamente", "success");
    $(this).remove();
  });

}


function eliminar()
{
  let seleccion = null;

  if (seleccion = ay_seleccion()) {

    if( confirm("Esta seguro de eliminar") ){
      
      ajaxs({ id_motivo : seleccion.find("td:eq(0)").text() } , url_eliminar , { success : eliminar_seleccion });
    }
  }
}

function events()
{
  $("[name=tipos_movimientos]").on('change',function(){ window.location.href = this.value });
  $("#nuevo").on( 'click' , function(){  
    $("#modalAccion .modal-title").text("Crear"); 
    show_hide_modal("modalAccion" , true );
  });
  
  $("#guardar").on( 'click' , guardar_modificar_movimiento );
  $("#modificar").on('click' ,  modificar_accion );
  $("#ver").on('click' ,  ir_enlace );
  $("#eliminar").on('click' ,  eliminar );
  $("#modalAccion").on('shown.bs.modal  ' ,  function(){$("[name=nombre]").focus()} );

  eventos_predeterminados( 'table_select_tr' , '#table_motivos' , [false, 'select'] );
}

init(
  events,
  ajaxs_setting.bind( null , {} , {defaultErrorAjaxFunc_showfield : true}),
)

