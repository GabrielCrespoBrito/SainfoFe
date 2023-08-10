console.log("ACcion...")

var accion; 

function StateFormCreateOrEdit(crear){
	$("[name=crear]").val(crear);		
  $("#form-usuario [name=usuario]").removeAttr('disabled')
  $("#ModalNuevoUsuario").find(".modal-title").text("Nuevo Usuario");
}

function mostrar_modal_crear_usuario(){
	accion = "create";
	limpiar_modal_usuario();
	StateFormCreateOrEdit(true);
	show_hide_modal("ModalNuevoUsuario", true)
}

function limpiar_modal_usuario(){
	$("#form-usuario input[type=text]").val("");
	$("#form-usuario input[type=checkbox]").prop("checked", false);	
	$("#form-usuario input[type=hidden]").val("true");		
}

function modify_user_to_table(user){		
	console.log("add user to table" , data );
}			

function successAdicionalFunc()
{
	console.log("successAdiconal usuarios.index")
}

function success_modal_save(data){
	notificaciones("Se ha guardado exitosamente la información del usuario","success");  
	show_hide_modal("ModalNuevoUsuario" , false );
	successAdicionalFunc();
  table.draw();    
}


// crear o editar un usuario
function crear_editar_usuario(e)
{
	let url_store = $("#ModalNuevoUsuario").data('urlstore');
	let url_update = $("#ModalNuevoUsuario").data('urlupdate');	

	e.preventDefault();
	let form = $(this).parents("#form-usuario");

	let data = {
		id   : form.find("[name=id]").val(),		
		codigo   : form.find("[name=codigo]").val(),
		nombre   : form.find("[name=nombre]").val(),
		usuario  : form.find("[name=usuario]").val(),
		email    : form.find("[name=email]").val(),
		password : form.find("[name=password]").val(),
		password_confirmation : form.find("[name=password_confirmation]").val(),			
		telefono : form.find("[name=telefono]").val(),
		direccion: form.find("[name=direccion]").val(),
		crear    : form.find("[name=crear]").val(),
		roles : null,
	};


  var values = form.find(".roles:checked").map(function(){return $(this).val();}).get();
  console.log("values de roles " , values );
  data.roles = values;

  console.log("data update" , data );

	var funcs = {
    success: success_modal_save,
    complete : () => {
      $("#load_screen").hide();
    },
	};

  let url = accion == "create" ? url_store : url_update;

	ajaxs( data , url , funcs );
};


function initDatable()
{
	let url = $('#datatable').data('url');

  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "rowCallback": function(row,data){
    	// console.log("arguments rowCallback" , arguments);
    	$(row).attr('data-info' , JSON.stringify(data) );
    },
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": url,
    "columns" : [      
      { data : 'usucodi'  },
      { data : 'usulogi' },
      { data : 'empresas' },
      { data : 'roles' },      
      { data : 'estado' , searchable: false },
      { data : 'btn', searchable: false   },      
    ]
  });
}

// poner información del usuario en el modal
function modal_info_modificar_usuario(e)
{
	e.preventDefault();
	limpiar_modal_usuario();
	$("#form-usuario [name=codigo]").attr("readonly","readonly");
	$("[name=crear]").val(false);


  $("#ModalNuevoUsuario").find(".modal-title").text("Modificar Usuario");

	let data = $(this).parents("tr").data("info");

	$("#form-usuario [name=id]").val( data.usucodi );	
	$("#form-usuario [name=codigo]").val( data.usucodi );
	$("#form-usuario [name=cargo]").val( data.carcodi );
	$("#form-usuario [name=nombre]").val( data.usunomb );

	$("#form-usuario [name=usuario]")
  .attr('disabled', 'disabled')
  .val( data.usulogi );

	$("#form-usuario [name=telefono]").val( data.usutele );
	$("#form-usuario [name=direccion]").val( data.usudire );
	$("#form-usuario [name=email]").val( data.email );

	// let roles = data.roles.split(" ");
	// if( roles.length ){
	// 	$(".roles").each(function(index,dom){
	// 		if( roles.includes( $(this).data('name')) ){
	// 			$(this).prop('checked', true);
	// 		}
	// 	});		
	// }

	$("#form-usuario [name=email]").val( data.email );

	show_hide_modal("ModalNuevoUsuario", true )
}

function showModalCreateEdit (e) {

  e.preventDefault();

  let $ele = $(e.target);  
  const url = $ele.attr('href')
  let titulo = $ele.is('.crear-nuevo-usuario') ? 'Nuevo Usuario' : 'Modificar Usuario';
  modalUser(url, titulo);
}


// poner información del usuario en el modal
function modalUser(url,titulo) {
    ajaxs( {} , url, { success : html => {
      const $modal = $("#modalData");
      $modal.find('.modal-title').text(titulo);
      $modal.find('.modal-body').empty()
      $modal.find('.modal-body').append(html)
      $modal.modal(true);
    }})
}




// Eliminar usuario
function modalConfirmacionEliminarUsuario(e)
{
	e.preventDefault();
	$("#eliminar-user [name=codigo]").val("01");
	$("#ModalEliminarNuevoUsuario").modal();
}

function activarFormEliminarUsuario()
{
	$("#eliminar-user").submit();
}



function selectAllPermissions(e)
{
  e.preventDefault();
  const $ele = $(e.target);
  const $permissions = $("#modalData").find('.permission-checkbox');
  const isSelected = $ele.attr('data-selected') == "1";
  isSelected ? $ele.text('Seleccionar todo') : $ele.text('Quitar selección');
  $ele.attr('data-selected', Number(!isSelected));
  $permissions.prop('checked', !isSelected )
}

function enviarFormulario(e) {

  $("#load_screen").show();
  e.preventDefault();
  const $ele  = $(e.target);
  const data = $ele.serialize();
  const url = $ele.attr('action');
  ajaxs(data,url,{ success : data => {
    notificaciones('Acción Exitosa', 'success');
    $("#load_screen").hide();
    setInterval(() => {
      location.reload();
    }, 1000);
  },
  complete : () => {
    $("#load_screen").hide();
  }
})

}


function events()
{
	// modal para crear o editar nuevos usuarios
	$(".send_user_info").on( 'click', crear_editar_usuario );

  $("#modalData").on(
    'click',
    '.select-all',
    selectAllPermissions
    );


  

	// boton para modificar usuario
  /*
  $(".user-table").on('click', '.modificar-usuario', modal_info_modificar_usuario);
  $("#table-users").on('click', '.modificar-usuario', modal_info_modificar_usuario);
	$(".crear-nuevo-usuario").on('click' , mostrar_modal_crear_usuario );
  */
  $(".crear-nuevo-usuario, .modificar-usuario").on('click', showModalCreateEdit);
  
  
  $("body").on('click', '.modificar-usuario', showModalCreateEdit);

  $("#modalData").on('submit', '#form-usuario', enviarFormulario );
  

	// activar modal para eliminar usuario
	$(".eliminar_user").on( 'click', modalConfirmacionEliminarUsuario);

	// mandar formulario para borrar el usuario		
	$(".aceptar_eliminacion").on('click' , activarFormEliminarUsuario );

	// boton para mostrar modal para usuario crear nuevo usuario
}

init( events , initDatable );

Helper.init()