function sendFormPrincipal(e)
{
  let $form = $(this);
  let data = $form.serialize();
  e.preventDefault();

  var url = $(this).attr('action');

  $("#load_screen").show();

  var funcs = {
    success: function() {
      notificaciones('Se ha modificado exitosamente la información del plan', 'success' );
      $("#modalData").modal('hide');
      table.draw();
    },
    complete : function(){
      $("#load_screen").hide();
    }
  };

  ajaxs(data, url, funcs);

  return false;
  
}

function calculateTotal(e)
{
  var $this = $(this);
  var $base = $("[name=base]", "#modalData");
  var $igv = $("[name=igv]", "#modalData");
  var $total = $("[name=total]", "#modalData");

  var igv_porc = Number($igv.attr('data-porc'));

  // 
  var base_value = 0;
  var igv_value = 0;
  var total_value = 0;

  console.log(
    'Number($base.val()),',
    Number($base.val()),
    Number($total.val())
  );

  if ( $base.val() == "" || $total.val() == "") {
    $base.val(0);
    $igv.val(0);
    $total.val(0);
    return
  }


  if ($this.is($base)) {
    var base_value = Number($base.val())
    var igv_value = (base_value / 100) * igv_porc;
    $igv.val(fixValue(igv_value));
    $total.val(fixValue(base_value + igv_value));
    return;
  }

  else {
    var total_value = Number($total.val())
    igv_porc = Number("1.".concat(igv_porc));
    var base_value = total_value / igv_porc;
    var igv_value = total_value - base_value;
    $igv.val(fixValue(igv_value));
    $base.val(fixValue(base_value))
  }
}

function sendFormUpdateCaracteristica(e)
{
  console.log("sendFormUpdateCaracteristica");
  
  let $parent = $(this).parents('.parent-caracteristica');

  e.preventDefault();  
  $("#load_screen").show();
  
  var data = {
    'nombre'    : $parent.find('[name=nombre]').val(),
    'adicional': $parent.find('[name=adicional]').val(),
    'value': $parent.find('[name=value]').val(),
  };

  var url = $(this).attr('data-url');
  var funcs = {
    success: function(data){
      console.log("success" , data );
      notificaciones("Acción Exitosa", 'success');
    },
    complete : function(){
      $("#load_screen").hide();
    }
  }

  ajaxs(data, url, funcs);
  return false;
}

function sendFormDeleteCaracteristica(e)
{
  console.log("sendFormDeleteCaracteristica");    
  
  e.preventDefault();
  window.parent_caracteristica = $(this).parents('.parent-caracteristica');

  $("#load_screen").show();
  var data = {};
  var url = $(this).attr('data-url');

  var funcs = {
    success: function (data) {
      console.log("success", data);
      notificaciones("Acción Exitosa", 'success');
      window.parent_caracteristica.hide(1000, function(){
        window.parent_caracteristica.remove();
      });
    },
    complete: function () {
      $("#load_screen").hide();
    }
  }

  ajaxs(data, url, funcs);
  return false;  
}



function sendFormCreateCaracteristica(e) {

  
  e.preventDefault();
  var $form = $(e.target);
  console.log("sendFormCreateCaracteristica");
  
  $("#load_screen").show();
  var data = $(e.target).serialize();
  var url = $form.attr('action');
  
  var funcs = {
    success: function (data) {
      console.log("success", data);
      notificaciones("Acción Exitosa", 'success');
      // window.parent_caracteristica.hide(1000, function () {
      //   window.parent_caracteristica.remove();
      // });
    },
    complete: function () {
      $("#load_screen").hide();
    }
  }

  ajaxs(data, url, funcs);
  return false;
}




function calculateDcto(e)
{
  var $this = $(this);
  var total = Number($("[name=total]", "#modalData").val());

  var $descuento_porc = $("[name=descuento_porc]", "#modalData");
  var $descuento_value = $("[name=descuento_value]", "#modalData");

  // var $ = $("[name=descuento_porc]", "#modalData");
  

  if (isNaN($descuento_porc.val()) || isNaN($descuento_value.val())) {
    $descuento_porc.val(0);
    $descuento_value.val(0);
    return
  }

  if ($this.is($descuento_porc)) {
    var descuento_value = (total /100) *   Number($descuento_porc.val())
    $descuento_value.val(fixValue(descuento_value));
  }

  else {
    var descuento_porc = (Number($descuento_value.val()) * 100) / total;
    $descuento_porc.val(fixValue(descuento_porc));    
  }

}

function showModalForm(html)
{

  $("#modalData").find('.modal-body')
  .empty()
  .append(html)

  let plan_nombre = "MODIFICAR: " +  $("#modalData").find('[name=codigo]').val();

  $("#modalData").find('.modal-header').html(plan_nombre)

  $("#modalData").modal();
}

function showFormEdit(e)
{
  e.preventDefault();
  var data = {};
  var url = $(this).attr('data-url');
  var funcs = { 
    success : showModalForm
  };
  ajaxs( data , url , funcs );
  return false;
}

function events()
{
  $("[name=tipo]").on('change' , function(e){
    var showEmpresaDiv = $(this).find('option:selected').val() == 'empresa';
    if (showEmpresaDiv ){
      $(".empresa_parent").show();
    }
    else {
      $(".empresa_parent").hide();
    }
  })

  $("[name=empresa_id],[name=tipo]").on('change', function (e) { 
    table.draw();
  })
 
  $("#datatable").on('click','.edit-btn',showFormEdit );
  $("#modalData").on('keyup', '[name=base],[name=total]', calculateTotal );
  $("#modalData").on('keyup', '[name=descuento_value],[name=descuento_porc]', calculateDcto );
  $("#modalData").on('submit', '#form-principal', sendFormPrincipal);
  $("#modalData").on('click', '.btn-caracteristica-update', sendFormUpdateCaracteristica );
  $("#modalData").on('click', '.btn-caracteristica-delete', sendFormDeleteCaracteristica);
  $("#modalData").on('submit', '#form-caracteristica', sendFormCreateCaracteristica);


}

function initDatatable()
{
  var columns = [
    { data: 'link'    },
    // { data: 'empresa' },
    { data: 'total'   },
    { data: 'accion'  },
  ];
  
  var options = {
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "tipo": $("[name=tipo]").val(),
          "empresa_id": $("[name=empresa_id]").val(),
        });
      }
    },
    "columns": columns
  }

  table = $('#datatable').DataTable(options);
}


$(document).ready(function(){

init(initSelect2, initDatatable, events);

})


