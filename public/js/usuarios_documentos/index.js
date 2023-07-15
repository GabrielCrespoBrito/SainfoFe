var accion;

function StateFormCreateOrEdit(crear) {
  $("[name=crear]").val(crear);
}

function fix_codigo_usuario(codigo = last_code, incrementar = true) {
  let numero = Number(codigo);

  if (incrementar) {
    return numero < 9 ?
      ("0" + (numero + 1)) :
      (numero + 1);
  }
  else {
    return numero < 9 ? ("0" + numero) : numero;
  }
}

function mostrar_modal_crear_usuario() {
  accion = "create";
  limpiar_modal_usuario();
  StateFormCreateOrEdit(true);
  show_hide_modal("ModalNuevoUsuario", true)
}

function limpiar_modal_usuario() {
  $("#form-usuario input").val("");
  $("#form-usuario input[type=hidden]").val("true");
}

function modify_user_to_table(user) {
}

function success_modal_save(data) {
  $("#form-usuario input").not("[type=hidden]").val("");
  notificaciones("Se ha guardado exitosamente la información del usuario", "success");
  show_hide_modal("ModalNuevoUsuario", false);
  if (data.redirect) {
    location.href = data.url;
  }
  else {
    table.draw();
  }
}

function setOrGetInputValue(input, value = null) {
  input = $(input);
  if (value === null) {
    return input.val()
  }
  else {
    input.val(value);
  }
}

// crear o editar un usuario
function crear_editar_usuario(e) {
  e.preventDefault();
  let form = $(this).parents("#form-usuario");
  let data = {
    id: "1",//, form.find("[name=id]").val(),		
    codigo: form.find("[name=codigo]").val(),
    nombre: form.find("[name=nombre]").val(),
    usuario: form.find("[name=usuario]").val(),
    email: form.find("[name=email]").val(),
    password: form.find("[name=password]").val(),
    password_confirmation: form.find("[name=password_confirmation]").val(),
    telefono: form.find("[name=telefono]").val(),
    direccion: form.find("[name=direccion]").val(),
    crear: form.find("[name=crear]").val(),
  };

  var funcs = {
    success: success_modal_save,
  };

  let url = accion == "create" ? url_store : url_update;

  ajaxs(data, url, funcs);
};


function initDatable() {
  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": {
      "url": url_consulta,
      "data": function (d) {
        return $.extend({}, d, {
          "empresa_id": $("[name=empresa_id]").val(),
          "local_id": $("[name=local_id]").val(),
          "user_id": $("[name=user_id]").val(),
        });
      }
    },
    "columns": [
      { data: 'ID' },
      { data: 'user.usulogi' },
      { data: 'loccodi' },
      { data: 'tidcodi' },
      { data: 'sercodi' },
      { data: 'numcodi' },
      { data: 'estado' },
      { data: 'acciones' },
    ]
  });
}



// poner información del usuario en el modal
function modal_info_modificar_usuario(e) {
  e.preventDefault();

  limpiar_modal_usuario();
  $("#form-usuario [name=codigo]").attr("readonly", "readonly");
  $("[name=crear]").val(false);

  let data = JSON.parse($(this).parents("tr").attr("data-info"));
  // --------------------------------------------------------------
  $("#form-usuario [name=id]").val(data.usucodi);
  $("#form-usuario [name=codigo]").val(fix_codigo_usuario(data.usucodi, false));
  $("#form-usuario [name=cargo]").val(data.carcodi);
  $("#form-usuario [name=nombre]").val(data.usunomb);
  $("#form-usuario [name=usuario]").val(data.usulogi);
  $("#form-usuario [name=telefono]").val(data.usutele);
  $("#form-usuario [name=direccion]").val(data.usudire);
  $("#form-usuario [name=email]").val(data.email);

  show_hide_modal("ModalUsuario", true)
}

// Eliminar usuario
function modalConfirmacionEliminarUsuario(e) {
  e.preventDefault();
  $("#eliminar-user [name=codigo]").val("01");
  $("#ModalEliminarNuevoUsuario").modal();
}

function activarFormEliminarUsuario() {
  $("#eliminar-user").submit();
}


function events() {
  // modal para crear o editar nuevos usuarios
  $(".send_user_info").on('click', crear_editar_usuario);

  // boton para modificar usuario
  $(".user-table").on('click', '.modificar-usuario', modal_info_modificar_usuario);

  // activar modal para eliminar usuario
  $(".eliminar_user").on('click', modalConfirmacionEliminarUsuario);

  // mandar formulario para borrar el usuario		
  $(".aceptar_eliminacion").on('click', activarFormEliminarUsuario);

  // boton para mostrar modal para usuario crear nuevo usuario
  $(".crear-nuevo-usuario").on('click', mostrar_modal_crear_usuario);

  // $("[name=empresa_id]").on('change', function () {
  //   consultLocals();
  // })

  $("[name=empresa_id],[name=local_id],[name=user_id]").on('change', function () {

    table.draw();

    let url = $(".crear-nuevo-usuario")
      .attr('data-route')
      .replace('@@@', $("[name=empresa_id] option:selected").val());

    $(".crear-nuevo-usuario").attr('href', url);
  })

  $("[name=empresa_id],[name=local_id]").on('change', function () {
    setTimeout(() => {
      console.log("Consultando TimeOut");
      consultUsers();
    }, 2000);
  })



}


function consultUsers() {
  const $local = $("[name=local_id]");
  let value = $local.find('option:selected').val();  

  let interval = setInterval(() => {
    value = $local.find('option:selected').val();
    if (value != undefined) {
      ajaxs({
          'empresa_id': $("[name=empresa_id] option:selected").val(),
          'local_id': $("[name=local_id] option:selected").val(),
        }, url_consulta_users, 
        {
          success: res => {
            const users = res.data;
            $("#user_id").empty();
            if (users.length) {
              let options = [];
              options.push( `<option value=""> - TODOS - </option>`);
              for (let i = 0; i < users.length; i++) {
                const element = users[i];
                const option = `<option ${element.selected ? 'selected' : ''} value="${element.id}"> ${element.text}</option>`;
                options.push(option)
              }
              $("#user_id").append(options);
            }

            table.draw();

            // ---------
          },
          complete: res => {
            console.log("complete", res)
          },
        },
      );
      clearInterval(interval);
    }
  }, 300);

  // }
}

init(events, initDatable, initSelect2, consultUsers);