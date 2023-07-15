$(document).ready(function () {

  let table_ = $('#table-empresa')
  window.table = table_.DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": $("#table-empresa").attr('data-url'),
    "columns": [
      { data: 'code' },
      { data: 'razon_social' },
      { data: 'ruc' },
      { data: 'email' },
      { data: 'telefono' },
      { data: 'cant_docs', 'searchable' : false, 'ordering' : false },
      { data: 'column_accion' },
    ]
  });

  $(".parent-main").on('click', '.remove-serie' , function (e) {

    e.preventDefault();
    $(this).parents('.parent-serie').remove();

  })


  $(".agregate-serie").on('click' , function(e){
    e.preventDefault();
    
    let serie_component = $(".serie-principal").clone(false);
    
    console.log(serie_component);
      
    serie_component
    .removeClass('serie-principal')
    .appendTo(".parent-main");

    serie_component.find('.agregate-serie')
    .removeClass('agregate-serie btn-primary')
    .addClass('remove-serie btn-danger')
    .find('span')
    .removeClass('fa-plus')
    .addClass('fa-trash')
  
    serie_component.find('select')
    .prop('selected' , false)

    serie_component.find('input').val('')

    $(".parent-main").append(serie_component);
  })


  $("#form-empresa").on('submit' , function(e){
    
    e.preventDefault();

    let valid = true;

    let url = $(this).attr('action');
    let data = new FormData(document.getElementById('form-empresa'));

    $.ajax({
      type: 'post',
      url: url,
      data: data,
      processData: false,
      contentType: false,
      success: (data) => {
        console.log(data);
        notificaciones(data.data, 'success');
        setTimeout(() => {
          location.href = data.redirect;          
        }, 500);
      },
      complete : (data) => {
        console.log({ 
          data 
        });
      },
      error: function (data) {
        defaultErrorAjaxFunc(data);
      },        
    });

    // ajaxs( url , data , funcs );

    console.table(url,data);

    e.preventDefault();
    return false;
  });
  
  Helper.init();

})