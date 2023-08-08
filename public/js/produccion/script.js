H = Helper__;

function createProductoInputs(e) {
  
  e.preventDefault();

  const $plantillaForm = $('.plantilla-product').clone()

  $plantillaForm.
  removeClass('plantilla-product')
  .show()
  .appendTo('#container-productos-insumos')

  $plantillaForm
    .find('.select2-container--default')
    .remove()

  initSelect2($plantillaForm.find('select'));
  }

function eliminarProductoForm(e) {
  e.preventDefault();

  let $btn = $(e.target);

  $btn.parents(".producto-forms").empty();

}

function enviarFormulario(e) {
  
  e.preventDefault();

  $("#load_screen").show();

  const $form = $(e.target);
  const data = $form.serialize();
  const url = $form.attr('action');
  const funcs = {
    success : (data) => {
      notificaciones('Accion Exitosa', 'success');
      setTimeout(() => {
        location.href = $(".salir-btn").attr('href')
      }, 1500)    
    },
    complete: (data) => {
      $("#load_screen").hide();
      console.log("complete", data)
    },
  };

  ajaxs(data,url, funcs)
}

H.add_events(function(){
  $(".btn-nuevo-producto").on('click', createProductoInputs)
  $("#form_principal").on('click', '.btn-eliminar-producto', eliminarProductoForm  )
  $("#form_principal").on('submit', enviarFormulario)
})

H.init();