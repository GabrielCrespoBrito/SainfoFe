var accion; 

function initDatable()
{
  let $selectStatus = $("[name=status]");

  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": {
      "url": url_consulta,
      "data": function (d) {
        return $.extend({}, d, {
          "status":  $selectStatus.val(),
        });
      }
    },
    "columns" : [      
      { data : 'empcodi' },
      { data : 'EmpNomb' },
      { data : 'EmpLin1' },
      { data : 'estado' },      
      { data : 'accion' },      
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
  
  let data = JSON.parse( $(this).parents("tr").attr("data-info") );

  $("#form-usuario [name=codigo]").val( fix_codigo_usuario( data.usucodi,false) );
  $("#form-usuario [name=cargo]").val( data.carcodi );
  $("#form-usuario [name=nombre]").val( data.usunomb );
  $("#form-usuario [name=usuario]").val( data.usulogi );
  $("#form-usuario [name=telefono]").val( data.usutele );
  $("#form-usuario [name=direccion]").val( data.usudire );
  $("#form-usuario [name=email]").val( data.email );

  show_hide_modal("ModalUsuario", true )
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


function events()
{
  $("[name=status]").on('change' , function(){
    table.draw();
  })

  // <form id="form-delete-empresa" data-url="{{ route('empresa.delete','@@') }}" method="post" action="#">

  $("*").on('click' , '.delete-empresa',  function(){


    let url =  $("#form-delete-empresa").attr('data-url');
    let id = $(this).parents('tr').find('td:eq(0)').text();

    console.log("delete", url , id );

    $("#form-delete-empresa").attr('action', url.replace('@@', id)  );
  })

}

init( events , initDatable );
