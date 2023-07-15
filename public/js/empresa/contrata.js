Helper.add_events(function(){

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


  // ckeditor

  $('#crear').on('click' , function(e){
  	
  	e.preventDefault();

  	let $this = $(this);
  	let url = $this.data('url');
  	let nombre = $("[name=nombre]");
  	// let contenido = $("#contenido").froalaEditor('html.get', true);
    let contenido = cont.getData();    
  	let method = $("[name=_method]");


  	if( nombre.val() == ""  ){
  		nombre.parents('.form-group').addClass('has-error');
  		notificaciones("El nombre es necesario" , "error");
  	}

  	console.log( url );
  	console.log( contenido );;

  	let data = {
  		nombre : nombre.val(),
  		contenido : contenido,  	
  		_method : method.val()	
  	}

  	let funcs = {
  		success : function(data){
        console.log("data", data );
  			notificaciones( data.message, "success" );
  			setTimeout(function(){	          
      		window.location.href = data.url;
  			}, 1500)

  		}
  	}

  	ajaxs( data , url , funcs  )

  });


})

Helper.init()



