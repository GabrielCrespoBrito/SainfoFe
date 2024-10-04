function active_or_disable_button( active = true , id = null )
{
  let modificar_button = $(".modificar-accion");    
  let eliminar_button = $(".eliminar-accion");
  let anular_button = $(".anular-accion");   
  let array_buttons = [ modificar_button , eliminar_button , anular_button ];    

  for( let i = 0; i < array_buttons.length; i++ ){
	if( active ){
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


function seleccionar_factura()
{    
  let tr = $(this);

  if( tr.find('.dataTables_empty').length  ) {         
	return;
  }

  if( tr.is('.seleccionado')) {         
	active_ordisable_trfactura(false);      
	active_or_disable_button(false)
  }

  else {

	$('.seleccionado').removeClass('seleccionado');

	active_or_disable_button(true)
	active_ordisable_trfactura(true, tr );          
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
	icon      : type,
	stack: false
  });
};

function defaultErrorAjaxFunc(data){
  // console.log( "error ajax" , data.responseJSON );
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  for( prop in errors ){
	for( let i = 0; i < errors[prop].length; i++  ){
	  erros_arr.push( errors[prop][i] );
	}
  }
  console.log("error" , erros_arr , mensaje );
  notificaciones( erros_arr , 'error' , mensaje ); 
}

function headerAjax(){
  $.ajaxSetup({
	headers: {
	  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
  });   
}


function ajaxs( data , url , funcs = {} )
{  
  funcs.mientras ? funcs.mientras() : null;
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
	  console.log("ajax terminado");
	  executing_ajax = false;        
	  funcs.complete ? funcs.complete(data) : null;
	}
  });  
};  

function fixedNumber(data,type,row,meta){
  return Number(data).toFixed(2);
}

function initDatable()
{
  window.table = $('#datatable').DataTable({
	"responsive" : true,
	"processing" : true,
	"serverSide" : true,
	"order": [[ 0, "asc" ]],
	"ajax": { 
	  "url" : url_consulta,
    "data": function (d) {
      return $.extend({}, d, {
        "deleted": $("[name=deleted]:checked").val(),
      });
    }
	},
	"columns" : [      
		{ data: 'MarCodi' },
		{ data: 'MarNomb' },
		{ data: 'acciones', 'searchable' : false },		
	]
  });

  if(create){
    $("[name=id_grupo]").val(last_id);

	$("#modalProducto").modal();
  }

}



function eliminar_ele(e)
{
  e.preventDefault();
  if( confirm("Desea quitar el Documento?") ){
	let data = {       
	  id : $(".seleccionado td:eq(0)").text()
	};
	let funcs = {
	  success : borradoExitoso
	}
	ajaxs( data , url_eliminar  , funcs );
  }
}


$("[name=familia_filter], [name=deleted]").on('change', () => {
  table.draw()
});

function borradoExitoso(data){

  let tr = $(".seleccionado");
  notificaciones("Elemento eliminado exitosamente", "success")  
  tr.css('outline' , '2px solid red');
  tr.hide(1000 , function(){
	tr.remove();
	table.draw(); 
  });  
}

function send_info()
{
  let url = accion == 'create' ? url_guardar : url_editar;
  let data = {
		MarCodi : $("[name=id_grupo]").val(),
		MarNomb : $("[name=nombre]").val()
  };

  let funcs = {
		success: producto_guardado
  }

  ajaxs( data , url , funcs );  
}

function guardar_grupo(){
  send_info();
}

function editar_grupo(){
  accion = "editar";
  send_info();
}


function producto_guardado(data){
	console.log("guardado  ", data );
	notificaciones(  data.data , "success");
	last_id = data.last_id;
  $("#modalProducto").modal("hide");
  active_ordisable_trfactura(false);      
  active_or_disable_button(false)
  table.draw();
}


function editar_grupo()
{  
  accion = "editar";
  let GruCodi = $( 'td:eq(0)' , '.seleccionado').text();
  let GruNomb = $( 'td:eq(1)' , '.seleccionado').text();
  $("[name=id_grupo]")
  .attr('readonly' , 'readonly')
  .val( GruCodi );
  $("[name=nombre]").val( GruNomb );
  $("#modalProducto").modal();
}

function nuevo_grupo()
{
  accion = "create";

  $("[name=id_grupo]")
  .removeAttr('readonly')
  .val(last_id);
  $("[name=nombre]").val("");
  $("#modalProducto").modal();
}




function events()
{
  $(".save").on( 'click' , guardar_grupo );

  $(".eliminar-accion").on( 'click' , function(e){
	  
	  e.preventDefault();     
	  
	  let tr = $('.seleccionado');

	  if( !tr.length ){
		return;
	  }

	  if(confirm("Esta seguro que desea eliminar?")){
		
		let formData = {          
		  'MarCodi' : tr.find('td:eq(0)').text() 
		};

		ajaxs( 
		  formData , 
		  url_eliminar , 
		  {
			success : function(data){
				notificaciones(data.data, "success");
				last_id = data.last_id;
			  tr.hide(500, function(){
					table.draw()
					// $(this).remove();
			  })
			},
			// error : function(data){
			//   console.log("erro" , data.responseJSON.message);
			//   notificaciones(data.responseJSON.message, "error")
		  // }
		});
	  }
	});  



  $("#datatable").on( 'click' , 'tbody tr', seleccionar_factura);    
  $("#nuevo_grupo").on( 'click' , nuevo_grupo );     
  $("#editar_grupo").on( 'click' , editar_grupo );   
  $("#borrar_grupo").on( 'click' , borrar_grupo );   
}

function init()
{
  initDatable();  
  events();
  headerAjax();
}

$(document).ready(init);