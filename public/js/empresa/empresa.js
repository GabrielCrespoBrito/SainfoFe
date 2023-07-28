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


function buscarRuc(e)
{
  e.preventDefault();
  const ruc = $("[name=ruc]").val();
  const url =  $(".search-ruc").attr('data-url');

  $("#load_screen").show();

  if( ruc.length == 0 ){
    notificaciones('Introducir el ruc por favor');
    return;  
  }

  const data = {
    numero: ruc
  }

  const funcs = {
    success: function(data){

      if( data.success ){
        $("[name=nombre_empresa]").val(data.data.razon_social);
        $("[name=nombre_comercial]").val(data.data.razon_social);
        $("[name=direccion]").val(data.data.direccion);
        $("[name=ubigeo]").val(data.data.ubigeo);

        if(data.data.ubigeo){
          
          
          
          let ubigeos = data.data.ubigeo_nombre.split('-');
          
          console.log(ubigeos);

          let departamento = ubigeos[1]
          let distrito = ubigeos[2]
          let provincia = ubigeos[3];


          $("[name=ubigeo]").val(data.data.ubigeo);
          $("[name=departamento]").val(departamento);
          $("[name=distrito]").val(distrito);
          $("[name=provincia]").val(provincia);

        }


        // ubigeo
      }
    },
    complete: function (data) {
      $("#load_screen").hide();

    }
  }

  ajaxs(data, url, funcs)

    // $.ajax({
    //   type: 'post',
    //   url: url,
    //   data: {
    //     numero : ruc
    //   },
    // });
}

function events()
{
  $(".search-ruc").on('click', buscarRuc )


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