$(document).ready(function () {
  
  let $tableResult = $("#table-result");

  function cleanTableResult()
  {
    $tableResult.find('tbody tr').each(function(){
      $(this).addClass('no-result');
      $(this).find('.status-value .value').val(0);
    })
  }

  function showTableResult() 
  {
    
    $(".result-process").removeClass('hide');
  }

  function hideTableResult() 
  {
    $(".result-process").addClass('hide');
  }

  function setDataTableResult(data)
  {
    let codesCount = {};

    for (let index = 0; index < data.length; index++) {

      let e = data[index];

      if ( ! codesCount[e.codigo]  ){
        codesCount[e.codigo] = {
          total : 1,
          code : e.codigo
        };
      }
      else {
        codesCount[e.codigo].total++;
      }

    }

    console.log(codesCount);

    // console.log("setDatatableresult" , data );
    for ( prop in codesCount ) {
      let code = codesCount[prop];
      let $tr = $tableResult.find('tr[data-code=' + code.code +  ']');
      $tr.removeClass('no-result');
      $tr.find('.status-value .value').text(code.total);
    }

    $("#table-result").find('.total').text(data.length);


  }  



  initSelect2('[name=empresa_id]')

  function setStyle(value, z, data , tr )
  {
    let td = 
    "<span class='status status-code-" +  
    value +  
    "'>" + 
    value +
    "</span>";
    
    console.log(td);
    
    return td;
  }

  function setDataTotable(data)
  {
    let table = $("#table-busqueda");
    let columns = [
      { name: 'descripcionSerie' },
      { name: 'numero' },
      { name: 'codigo' , render : setStyle },
      { name : 'descripcion' },
    ]
    let isFirst = true;

    // for( code in data ){
      add_to_table("#table-busqueda", data, columns );
      isFirst = false;      
    // }
  }




  $("#formBuscador").on('submit', function (e) {

    let url = $(this).attr('action'),
      data = $(this).serialize(),
      $numeroInicial = $("[name=numero_inicial]"),
      $numeroFinal = $("[name=numero_final]");
      hasError = false;
    errorMessage = '';

    if (isNaN($numeroInicial.val()) || $numeroInicial.val() == "") {
      $numeroInicial.focus()
      notificaciones('El numero inicial debe ser númerico', 'error');
      return false;
    }

    if (isNaN($numeroFinal.val()) || $numeroFinal.val() == "") {
      $numeroFinal.focus()
      notificaciones('El numero final debe ser númerico', 'error');
      return false;
    }

    if (Number($numeroInicial.val()) > Number($numeroFinal.val())) {
      $numeroInicial.focus()
      notificaciones('El numero final debe ser mayor al numero final', 'error');
      return false;
    }

    hideTableResult();
    $("#table-busqueda tbody").empty();

    $(".submit")
    .prop('disabled' , true )
    .html("<span class='fa fa-spin fa-spinner'></span> Buscando...");


    $.ajax({
      type: 'post',
      url: url,
      data: data,
      success: (data) => {
        setDataTotable(data.data);

        cleanTableResult();
        showTableResult();
        setDataTableResult(data.data);
        notificaciones("Busqueda finalizada", 'success');
      },
      complete: (data) => {
        $(".submit")
        .prop('disabled', false)
        .html("<span class='fa fa-search'></span> Buscar"          
        )    
      },
      error: function (data) {
        defaultErrorAjaxFunc(data);
        
      },
    });

    console.table(url, data);

    e.preventDefault();
    return false;
  });

  $("[name=empresa_id]").on('change' , function(){
    location.href =  $( 'option:selected', this).val();
  })


})
