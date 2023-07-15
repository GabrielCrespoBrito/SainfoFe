console.log("hola mundoccc");


function bloquear_facturacion_electronica()
{
	// show_hide_element( ".block" , !document.querySelector("[name=facturacion]").checked )
}

function cambiar_url()
{
	let url = $("[name=tipo_envio_servicio] option:selected").data('url');
	$("[name=url_servicio]").val(url);
}

function change_type_input(e)
{
	let input = $(this).parent().find("input");
	let new_type = input.is('[type=password]') ? 'text' : 'password';
		input.attr('type', new_type);
	console.log("e,new_type",e,new_type);
}


function ask_deleting(e)
{
	console.log("borrando");
	e.preventDefault();
	e.stopPropagation();
	if(confirm('Esta seguro que desea eliminar la imagen')){
		let url_delete = $(this).data('form_action');
		$("#form-delete")
		.attr('action', url_delete )
		.submit();
	}

}

function sendForm(e)
{
	let $form = $("#form-create-empresa");
	let url = $form.attr('action');
	let success = function(data){
		// console.log("EXITO AJAX", data);
	let href = $(".link-salir").attr('href');
	location.href = href;
		// $(".block_elemento").show();
	} 

	$(".block_elemento").show();

	var formData = new FormData( document.querySelector('#form-create-empresa') );

	// var formData = $form.serialize();
	// formData.append('id_grupo', id_grupo);
	// formData.append('id_grupo', id_grupo);

	console.log("formData" , formData);

	// $.each(inputs, function (obj, v) {
	// 	var file = v.files[0];
	// 	var filename = $(v).attr("data-filename");
	// 	var name = $(v).attr("id");
	// 	formData.append(name, file, filename);
				
	// });
	
	$.ajax({
		type: 'post',
		url: url,
		data: formData,
		processData: false,
		contentType: false,
		success: success ,
		error: function(data){
			defaultErrorAjaxFunc(data),
		$(".block_elemento").hide();
		},
		complete: function (data) {
			console.log("complete" , data );
		}
	});

	e.preventDefault();
	return false;
}


function events()
{
	$("[name=facturacion]").on('change' , bloquear_facturacion_electronica )
	$("[name=tipo_envio_servicio]").on('change' , cambiar_url )
	$(".c-input").on('click' , change_type_input )
	$(".delete_logo").on('click' , ask_deleting )
	$("#form-create-empresa").on('submit' , sendForm )
  initSelect2("#ubigeo");

  $("#ubigeo").on('select2:selecting', function (data) {


    let data_ubigeo = data.params.args.data.data;

    console.log("seleccionando ubigeo", data_ubigeo );
    let departamento = data_ubigeo.departamento.depnomb;
    let provincia = data_ubigeo.provincia.provnomb;
    let distrito = data_ubigeo.ubinomb;

    $("[name=departamento]").val(departamento);
    $("[name=provincia]").val(provincia);
    $("[name=distrito]").val(distrito);
  })

}


init(
	events,
	bloquear_facturacion_electronica,
	cambiar_url
);