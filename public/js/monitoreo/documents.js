$(document).ready(function(){

  initSelect2('[name=empresa_id]');

  $("[name=codigos],[name=mes],[name=status]").on('change' , () => window.table.draw()  );

  let table_ = $('#datatable')
  window.table = table_.DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "lengthMenu": [[10, 20, 50, 100, 250, 300, 500, -1], [10, 20, 50,100, 250, 300, 500, "TODOS"]],
    "order": [[0, "desc"]],    
    "ajax": {
      "url": table_.attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "serie_id": $("[name=serie_id] option:selected").val(),
          "empresa_id": $("[name=empresa_id] option:selected").val(),
          "mescodi": $("[name=mes] option:selected").val(),
          "status": $("[name=status] option:selected").val(),
          "codigo_status_id": $("[name=codigos] option:selected").val(),
        });
      }
    },
    "columns": [
      { data: 'serie', searchable: false },
      { data: 'numero', searchable: false },
      { data: 'mescodi', searchable: false  },
      { data: 'mescodi' , searchable : false  },
      { data: 'status_code', searchable: false , search: false },
      { data: 'status_message', searchable: false  },
    ]
  });

  $("[name=mes], [name=serie_id]").on('change' , () => table.draw());

  $("#formBuscador").on('submit', function (e) {

    let url = $(this).attr('action'),
    data = $(this).serialize(),
    $numeroInicial = $("[name=numero_inicial]"),
    $numeroFinal = $("[name=numero_final]");
    hasError = false;
    errorMessage = '';

    if (isNaN($numeroInicial.val()) || $numeroInicial.val() == "" ){
      $numeroInicial.focus()
      notificaciones('El numero inicial debe ser númerico', 'error');
      return false;
    }

    if (isNaN($numeroFinal.val()) || $numeroFinal.val() == "" ) {
      $numeroFinal.focus()
      notificaciones('El numero final debe ser númerico', 'error');      
      return false;
    }

    if (Number($numeroFinal.val()) > Number($numeroInicial.val()) ) {
      $numeroInicial.focus()
      notificaciones('El numero inicial debe ser mayor al numero final', 'error');
      return false;
    }    

    $.ajax({
      type: 'post',
      url: url,
      data: data,
      success: (data) => {
        console.log(data);
        notificaciones(data.data, 'success');
        $("#modalDocuments").modal('hide')
        window.table.draw();
      },
      complete: (data) => {
        console.log({
          data
        });
      },
      error: function (data) {
        defaultErrorAjaxFunc(data);
      },
    });

    e.preventDefault();
    return false;
  });


  $('[name=empresa_id]').on('change', function(){
    let href = $(this).find('option:selected').val();
    location.href = href;
  })

})