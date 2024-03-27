$(function () {
  
  let $formEle = $("form#elegirEmpresaPeriodo");

  function changeEmpresaByUrl() {

    let ruc = $("[name=empresa] option:selected").attr('data-ruc');

    const urlInstance = new URL(location.href);

    if ( urlInstance.host.includes('.') == false ) {
      return;
    }

    let rucUrl = urlInstance.host.split('.')[0];
    
    if(ruc != rucUrl){
       $(`[name=empresa] option[data-ruc=${rucUrl}]`).prop('selected', true);
    }
  }

  function changeUrl() {

    let ruc = $("[name=empresa] option:selected").attr('data-ruc');
    
    const urlInstance = new URL(location.href);
    
    // console.log(ruc, urlInstance);

    if(urlInstance.host.includes(ruc)){
      return;
    }
    
    let host = urlInstance.host;

    if(host.includes('.') ){
      host = host.split('.')[1];
    }

    console.log(
      `${urlInstance.protocol}//${ruc}.${host}${urlInstance.pathname}`

    )

    location.href = `${urlInstance.protocol}//${ruc}.${host}${urlInstance.pathname}`;
  }

  function setPeriodo()
  {
    let dataPeriodos = $("[name=empresa] option:selected").attr('data-periodo').split(",");
    let selectPeriodo = $("[name=periodo]", $formEle);
    selectPeriodo.empty();
    for (let i = 0; i < dataPeriodos.length; i++) {
      let periodo = dataPeriodos[i];


      let option = $("<option></option>")
        .attr('value', periodo)
        .text(periodo);

      if (periodo == (new Date()).getFullYear()) {
        option.prop('selected', true);
      }
      selectPeriodo.append(option);
    }

  }


  $formEle.on('submit', function (e) {
    $("[type=submit]").prop('disabled', true);
  });

  
  $formEle.find("[name=empresa]").on('change', setPeriodo);
  $formEle.find("[name=empresa]").on('change', changeUrl );
  
  changeEmpresaByUrl();
  changeUrl();

});