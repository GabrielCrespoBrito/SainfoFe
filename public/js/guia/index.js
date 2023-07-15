id_guia = null;

function isSalida()
{
  let $btn = $(".new-guia");

  let isSalida = $btn.length ? ($btn.attr('data-issalida') == 1) : true;
  
  return isSalida;
}


function initDatable()
{
  let url_search = $('#datatable').attr('data-url');;
  
  $("#datatable").one("preInit.dt", function () {
    let $button =
      $(`<select name="filtro" class='select-field-producto input-sm form-control'>
        <option value='codigo'>Nro.Oper</option>
        <option value='correlativo'>N° Doc</option>
        <option value='ref'>Ref</option>
        <option value='cliente'>Cliente</option>
        </select>`);
    $("#datatable_filter label").prepend($button);
  });


  let columnEstado = { 
    data : 'estado' ,
    searchable : false, 
    sortable : false
  };
  let columnAccion = { 
    data : 'accion' ,
    searchable : false, 
    sortable : false
  };                 
  
  let columns = [
    { data: 'nrodocumento', orderable: false, searchable: false },
    { data : 'GuiNume' ,  render : function(param1,param2,param3,param4){
      let data = param3;
      let nume =  data.GuiSeri == null ? data.GuiNume : ( data.GuiSeri + "-" + data.GuiNumee );
      return nume;      
    }, orderable: false, searchable: false  },
    { data : 'docrefe' , orderable: false, searchable: false },
    { data: 'GuiFemi', orderable: false, searchable: false},            
    { data : 'cli.PCNomb', orderable: false, searchable: false },  
    { data : 'almacen' , 
      render : function(param1,param2,param3,param4){
      return param1 == null ? '' : param1.LocNomb
      },
      searchable : false, 
      sortable : false
    },
    {
      data: 'estado',
      searchable: false,
      sortable: false
    },    
    {
      data: 'accion',
      searchable: false,
      sortable: false
    } 
  ];

  window.table = $('#datatable').DataTable({
    "responsive" : true,
    "processing" : true,
    "language": { search: "", searchPlaceholder: "Search..." },
    "serverSide" : true,
    "ajax": { 
      "url" : url_search,  
      "data": function ( d ) {
       return $.extend( {}, d, {
         "mes": $("[name=mes]").val(),
         "tipo_documento": $("[name=tipo_documento]").val(),
         "local": $("[name=local]").val(),
         "status": $("[name=status]").val(),
         "formato": $("[name=formato] option:selected").val(),
         "motivo_traslado": $("[name=motivo_traslado] option:selected").val(),
         "estado_traslado": $("[name=estado_traslado] option:selected").val(),
         "filtro": $("[name=filtro] option:selected").val(),
       });
      }
    },
    "columns" : columns
  });
}

function send_correo()
{
  desactivar_button(".send_correo");
  let data = {
    corre_hasta : $("[name=corre_hasta]").val(),
    asunto : $("[name=corre_asunto]").val(),
    corre_mensaje : $("[name=corre_mensaje]").val(),
    corre_mensaje : $("[name=corre_mensaje]").val(),
    id_guia : id_guia,    
  }
  let url = url_sendcorre_format.replace('@@', id_guia );
  ajaxs( data , url , {
    success : function(data){
      notificaciones("Correo enviado exitosamente", "success");
    },
    complete : function(data){
      show_hide_modal( "modalMail" , false );
      activar_button(".send_correo");
    }
  });
}

function activatePopover()
{
  var $btns = $("[data-toggle='popover-x']");
  if( $btns.length ){
    $btns.popoverButton({
      closeOpenPopovers: true,
      keyboard : false,
      placement: 'auto-left',
      backdrop : false,
      trigger : 'click'
    });
  }
}

function deleteGuia(e)
{
  e.preventDefault()
  if(confirm( 'Esta seguro que desea anular esta guia?' )){
    let id = $(this).data('formid');
    $form = $("#" +  id );
    $form.submit();
  }
}

function anularGuia(e)
{
  e.preventDefault()
  if(confirm( 'Esta seguro que desea anular esta guia?' )){
    // console.log( "console.log",  $(this).parents('td').find('form'));
    $(this).parents('td').find('form').submit();
  }
}

function redactar()
{
  $(".popover").hide();
  show_hide_modal("modalMail" , true)
}

function showHideSelects()
{
  showHideMotivoSelect();
  showHideEstadoTrasladoSelect();
}


function showHideMotivoSelect() {
  let formato = Number($("[name=formato]").val())

  if (formato) {
    $("[name=motivo_traslado]").show()
    $("[name=estado_traslado]").show();
  }
  else {
    $("[name=motivo_traslado]").hide()
    $("[name=estado_traslado]").hide();
  }
}

function consultTicket(e)
{
  e.preventDefault();
  $("#load_screen").show();
  ajaxs({}, e.target.getAttribute('href'), {
    success: function (data) {
      notificaciones(data.message, 'success')
    },
    error: function (data) {
      notificaciones(data.message, 'error')
    },
    complete: function (data) {
      $("#load_screen").hide();
    }
  });

  console.log("consultanmdo ticket");
}

function showHideEstadoTrasladoSelect() {

  let motivo_value = $("[name=motivo_traslado]").find('option:selected').val();
  motivo_value == "04" ? $("[name=estado_traslado]").show() : $("[name=estado_traslado]").hide();
}

function cambiarTipoDocumento(e)
{
  document.getElementById(e.target.dataset.target).setAttribute('href' , e.target.value );
}

function events()
{
  $("body").on('change', '[name=formato_pdf]', cambiarTipoDocumento );


  $("body").on('click', '.consult-ticket', consultTicket );  


  $("*").on('click','.redactar',redactar);  
  $("body").on( 'click', '.deleteBtn', deleteGuia );
  $("body").on('click', '.anularBtn', anularGuia);
  
  //
  $("body").on('change', '[name=filtro],[name=local]' , function(e){
    table.draw();
    e.preventDefault();
  });
  
  $( "[name=formato]" ).on('change' , function(){
    showHideSelects();
    table.draw();
  })

  $("[name=motivo_traslado]").on('change', function () {
    showHideEstadoTrasladoSelect()
    table.draw();    
  })

  $("[name=estado_traslado]").on('change', function () {
    table.draw();
  })

  $(".send_correo").on('click' , send_correo );
  // btn-popover 
  $("body").on('click', "[data-toggle='popover-x']" , function(){
    id_guia = $(this).parents('tr').find("td:eq(0)").text();
    show_hide_modal("modalMail",false);
    console.log("id_guia",id_guia);
  })

  table.on('draw.dt', activatePopover );
}

init(initDatable,events);
Helper__.init(showHideSelects);
