function successAdicionalFunc() {
  location.reload();
}

$(function () {

  $(".change-status").on('click', function (e) {
    e.preventDefault();
    if (confirm("Esta seguro se cambiar el estatus del usuario?")) {
      location.href = $(this).attr("href");
    }
  });

  $("*").on('change', '[name=tipo_usuario]', function (e) {

    const show = e.target.value == "02";

    $(".div-local, .div-permisos").toggle(show);

  });


  return false;
})