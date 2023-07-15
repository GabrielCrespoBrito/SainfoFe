$(document).ready(function (e) {

  $('.container-letras').on('click', '.delete-btn', function (e) {
    e.preventDefault();
    console.log("stop");
    let $this = $(this);
    $this.parents('.component-letras').remove();
  })


  function addLetra()
  {
    let elementLetra =
      `
      <div class="row component-letras">
        <div class="input-group col-md-3">
        <input type="hidden" name="PgoCodi" class="form-control">
        <input type="number" required name="PgoDias" class="form-control">
          <span class="btn input-group-addon delete-btn" style="color: red;"><i class="fa fa-minus"></i></span>
        </div>
      </div>
      `;

    $(".container-letras").append(elementLetra);

  }

  $('.add-letra').on('click', function (e) {
    e.preventDefault();
    addLetra();
  })


  $('[name=contipo]').on('change', function (e) {

    let showLetrasComponent = this.value == 'C';

    let componentsLength = $(".component-letras").length;

    if (showLetrasComponent && componentsLength == 0 ){
      addLetra();
    }
    showLetrasComponent ?
      $(".container-component-letras").hide() : 
      $(".container-component-letras").show();
  })



  $('.form-forma-pago').on('submit', function (e) {

    e.preventDefault();

    let tipo = $("[name=contipo]").val();

    let data = {
      'connomb': $("[name=connomb]").val(),
      'contipo': tipo,
      '_method': $("[name=_method]").val()
    };


    let dias = []

    $(".component-letras").each(function(index,dom){
      let data = {
        'PgoDias': $(this).find('[name=PgoDias]').val(),
        'PgoCodi': $(this).find('[name=PgoCodi]').val(),
      }
      dias.push(data);
    });

    
    if( tipo == "D"  && dias.length == 0){
      notificaciones("Tiene que agregar algun dia", 'error');
      return false;
    }

    $("#load_screen").show();

    data.items = dias;

    console.log("data", data );

    let funcs = {
      success : function(data){
        console.log("success",data);
        notificaciones(data.message,'success');
        setTimeout(() => {
          location.href = $(".link-index").attr('href');
        }, 500);
        return;
      },
      complete : function(data){
        $("#load_screen").hide();
      } 
    }

    ajaxs(
      data,
      $(this).attr('action'), 
      funcs
    )

    return false;

  })

  
  
})