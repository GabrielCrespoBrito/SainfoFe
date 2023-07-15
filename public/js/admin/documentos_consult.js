

console.log("AQUI ESTAMOS Y AQUI SEGUIMOS");

function changeEmpresa () {
  console.log("Aqui estamos");

  let $option = $(this).find('option:selected');
  let data = $option.data('info');

  console.log("data", data);

  const $ruc = $("[name=ruc_empresa]");
  const $usuario_sol = $("[name=usuario_sol]");
  const $clave_sol = $("[name=clave_sol]");

  $ruc.val(data.ruc)
  $usuario_sol.val(data.u_sol)
  $clave_sol.val(data.c_sol)

  $('.tab-content').addClass('resaltado-outline');

  setTimeout(() => {
    $('.tab-content').removeClass('resaltado-outline');
  }, 500 )
}

function changeTipo() {
  $("[name=tipo]").parents('label').removeClass('selected');

  $("[name=tipo]:checked").parents('label').addClass('selected');
}

function changeTipoDocumento () 
{

  const tidcodi = $(this).val();
  const $serie = $("[name=serie_documento]")

  let series = {
    '01' : 'F001',
    '03' : 'B001',
    '07' : 'F001',
    '08' : 'F001',
    '09' : 'T001',
  }
  $serie.val(series[tidcodi]);
}

function events()
{
  $("[name=empresa_id]").on('change', changeEmpresa )
  $("[name=tipo_documento]").on('change', changeTipoDocumento)
  $("[name=tipo]").on('change', changeTipo)
  
}



window.init(
  events
)