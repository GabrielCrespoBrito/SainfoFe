
$(document).ready(function () {

  $(".generateReport").on('click', function (e) {

    let url = $(this).data('url');


    url = url
      .replace('mes', $("[name=mes] option:selected").val())
      .replace('local_', $("[name=local] option:selected").val())
      .replace('tipo', $("[name=tipo] option:selected ").val())
      .replace('reprocesar', Number($("[name=reprocesar]").is(':checked')))
      .replace('formato', $("[name=formato] option:selected").val())

    console.log(url);

    $(this).attr('href', url);
  });

})