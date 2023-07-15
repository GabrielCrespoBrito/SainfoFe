
function makeEleError(error)
{
  if (error.length > 99) {
    error = `<abbr title="${error}"> ${error.substring(0, 99)}...</abbr>`;
  }
  return `<li>${error}</li>`;
}

function errorImportExcelll(data)
{
  $(".block_elemento").hide();
  
  const $errorsContainer = $("#errors-container");
  var $errors = `<ol class="errors-list">`;
  let titleMessage = `Error en la Importación`;

  console.log(data);
  
  if (data.responseJSON ){
    titleMessage = data.responseJSON.message;
    let errors = data.responseJSON.errors

    if( Array.isArray(errors) ){
      errors.map(error => $errors += makeEleError(error) );
    }
    else {
      for ( prop in errors ) {
        errors[prop].map((error) => $errors += makeEleError(error));
      }

    }
  }

  $errors += "</ol>";

  let $titleMessage = `<div class="title-error">${titleMessage}</div>`;
  
  console.log( $errors );

  $errorsContainer.empty();
  $errorsContainer.append($titleMessage, $errors );
  $errorsContainer.show();
  // ---------------------------------------------------------------
}

function events() {
  $(".send_info").on('click', function (e) {

    if (!confirm("Esta seguro que desea importar la información?")) {
      return false;
    }

    e.preventDefault();

    if (!$("[name=excel]").val()) {
      notificaciones("Tiene que subir un archivo excell", 'error');
      return;
    }

    let formData = new FormData();

    $(".block_elemento").show();
    formData.append('excel', $("[name=excel]")[0].files[0]);

    $("#errors-container").hide();

    $.ajax({
      type: 'post',
      url: $("#form-accion").attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {

        console.log("success", data );

        notificaciones( data.message , 'success' );
        $(".block_elemento").hide();
      },
      complete : function(data)
      {
        $(".block_elemento").hide();
      },
      error: errorImportExcelll,

        // window.defaultErrorAjaxFunc(data)
        // errorImportExcelll();
      // },

      complete: function (data) {
        console.log("complete", data)
      }
    });
  });
}

init(events);