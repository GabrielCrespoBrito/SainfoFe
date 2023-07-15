DecoupledEditor
.create( document.querySelector( '#editor' ), {
} )
.then( editor => {
const toolbarContainer = document.querySelector( 'main .toolbar-container' );

toolbarContainer.prepend( editor.ui.view.toolbar.element );

window.editor = editor;
} )
.catch( err => {
console.log( err );
} );



Helper.add_events(function(){



    return;

  $('#crear').on('click' , function(e){
  	
  	e.preventDefault();

  	let $this = $(this);
  	let url = $this.data('url');
  	let nombre = $("[name=nombre]");
  	// let contenido = $("#contenido").froalaEditor('html.get', true);
    let contenido = CKEDITOR.instances['contenido'].getData();    
  	let method = $("[name=_method]");


  	if( nombre.val() == ""  ){
  		nombre.parents('.form-group').addClass('has-error');
  		notificaciones("El nombre es necesario" , "error");
  	}


    console.log( contenido );;
    let data = {
      nombre : nombre.val(),
      contenido : contenido,    
      _method : method.val()  
    }


  	console.log( url , data );


  });


})

Helper.init()



