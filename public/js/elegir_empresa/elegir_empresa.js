$(function () {
  let $formEle = $("form#elegirEmpresaPeriodo");

  $formEle.on('submit', function (e) {
    $("[type=submit]").prop('disabled', true);
  });

  $formEle.find("[name=empresa]").on('change', function (e) {

    let dataPeriodos = $("option:selected", this).attr('data-periodo').split(",");
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

  });

});