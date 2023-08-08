H = Helper__;

function showSelectEdit(e)
{
  const $btnEdit = $(e.target);
  const $td = $btnEdit.parents('td');
  const $divEstado = $td.find('.div-estado-actual');
  const $divEstadoEdit = $td.find('.div-estado-edit');
  // const $btnEdit = $(e.target);
  // const $btnEdit = $(e.target);
  // console.log("showSelectEdit");

  $divEstado.hide();
  $divEstadoEdit.show();
}

function cancelEdit(e) {

  const $btnClose = $(e.target);
  const $td = $btnClose.parents('td');
  const $divEstado = $td.find('.div-estado-actual');
  const $divEstadoEdit = $td.find('.div-estado-edit');
  // const $btnClose = $(e.target);
  // const $btnClose = $(e.target);
  // console.log("showSelectEdit");
  $divEstado.show();
  $divEstadoEdit.hide();
}

function eliminarMessage(e) {
  e.preventDefault();
  const $btn = $(e.target);
  if( confirm('Esta Seguro de Eliminar Este Registro') ){
    $("form.eliminar").attr('action',  $btn.attr('href'));
    $("form.eliminar").trigger("submit")
  }
}

H.add_events(function(){

  $(".btn-edit-estado").on('click', showSelectEdit)
  $(".btn-edit-close").on('click', cancelEdit)
  $(".btn-eliminar").on('click', eliminarMessage  )
  console.log("hola mundo");
})

H.init();
Helper.init();