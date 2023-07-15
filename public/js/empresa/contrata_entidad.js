$(document).ready(function(){

  initSelect2();
  datepicker();


  let has_content = $('#contenido').length;

  if( has_content ){

  var cont;

    DecoupledEditor
    .create( document.querySelector( '#contenido' ), {
    } )
    .then( editor => {
      const toolbarContainer = document.querySelector( 'main .toolbar-container' );
      toolbarContainer.prepend( editor.ui.view.toolbar.element );
      cont = editor;
    } )
    .catch( err => {
    console.log( err );
    } );



    // $('#contenido').froalaEditor();    
  }

  $('#crear').on('click' , function(e){
    
    e.preventDefault();

    let $this = $(this);    
    let url = $this.data('url');

    let entidad_tipo = $("[name=entidad_tipo]");
    let entidad_id   = $("[name=entidad_id]");
    let documento_id = $("[name=documento_id]");
    let fecha_inicio = $("[name=fecha_inicio]");    
    let fecha_emision = $("[name=fecha_emision]");        
    let fecha_final  = $("[name=fecha_final]");        
    let contenido = $('#contenido');            
    let method = $("[name=_method]");
    let contenido_value =  has_content ? cont.getData() : null;

    if( has_content &&  contenido_value == ""){
      notificaciones("Es necesario el contenido del documento" , "error");
    }


    let data = {
      entidad_tipo : entidad_tipo.val(),
      entidad_id   : entidad_id.val(),
      documento_id : documento_id.val(),
      fecha_emision : fecha_emision.val(),
      

      fecha_inicio : fecha_inicio.val(),
      fecha_final  : fecha_final.val(),
      contenido    : contenido_value,
      _method      : method.val()  
    }

    let funcs = {
      success : function(data){
        notificaciones( data.message, "success" );
        console.log({data});
        setTimeout(function(){  
          window.location.href = data.url;
        }, 1000)

      }
    }

    ajaxs( data , url , funcs  )

  });



});
