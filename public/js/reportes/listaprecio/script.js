H = Helper__;

var inputsChecked = false;

function initDatable() {
  let table = $('#datatable')
  window.table = table.DataTable({
    "pageLength": 50,
  });
}

/**
 * Poner un color al tr si el elemento es checked
 * 
 * @return void
 */
function colorIsChecked( tr = false )
{
  if(tr){
    $(tr).find('input').is(':checked') ? $(tr).addClass('seleccionado') : $(tr).removeClass('seleccionado');
    return;
  }
  
  $("#datatable tbody tr").each(function(){
    colorIsChecked(this);
  });
}

/**
 * Checked/Unchecked al input al darle click al tr
 * 
 * @return void
 */
function checkedInput()
{
  let input = $(this).find('input');
  input.prop('checked',  ! input.is(':checked'));
  colorIsChecked(this);
}

/**
 * Checked/Unchecked a todos los input
 *
 * @return void
 */
function checkedAllInput() 
{
  $("#datatable input").prop('checked', ! inputsChecked )
  inputsChecked = ! inputsChecked;
  let text = inputsChecked ? 'Quitar todo' : 'Seleccionar todo';
  $(this).text(text);
  colorIsChecked();
}

function prepareForm(e)
{
  let grupos = [];
  $('.grupos-form').each(function(){
      if( $(this).is(':checked') ){
        grupos.push( this.value );
      }
  })

  // listaprecio / script.js

  if( grupos.length == 0 ){
    notificaciones('', '', 'No se puede enviar el formulario sin seleccionar al menos un grupo');
    e.preventDefault();
    return false;
  }
}


const cambiarTipoReporte = e =>
{
  e.preventDefault();

  const tipo = $(e.target).is('.pdf') ? 'pdf' : 'excell';
  $("[name=tipo_reporte]").val(tipo);
  $(".form-lista").trigger('submit');
  return false;
}


H.add_events(function () {
  initDatable();
  $("#datatable").on('click', 'tr', checkedInput)
  $(".form-lista").on('submit', prepareForm)
  $(".enviar-reporte").on('click', e => $(".form-lista").trigger('submit') );
  $(".tipo-reporte-btn").on('click', cambiarTipoReporte )
  
  $("#datatable thead td:last-child").on('click', checkedAllInput )
})

H.init();
Helper.init();