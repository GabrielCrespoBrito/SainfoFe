/******************************/

export function saludo(foo){
  console.log("hola " + foo);
}

export function modalDeleteElements(){
	
  console.log("modalDeleteElements (this) => ", this);

  $("body").on('click' , '.eliminate-element' , function(e){
	  e.preventDefault()
	  let modal = $("#modalEliminate")
	  let id = $(this).attr("data-id")    
	  let url = $("#formEliminate").attr('action').replace('XX' , id)
	  $("#formEliminate").attr('action' , url )
	  modal.modal()
  })
}

export function initDataTable(){
  let tabledatatable = $("table[data-datatable]");
  if( tabledatatable.length ){
    if( !tabledatatable.data('url') ){
      tabledatatable.DataTable()
    }
  }
}


/******************************/


