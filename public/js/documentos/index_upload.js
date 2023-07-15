function initDatable()
{
  table = $('#uploadTable').DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": {
			'url' : url_consulta,
      "data": function ( d ) {
				return $.extend( {}, d, {
					"tipo"    : $("[name=tipo] option:selected").val(),        
					"estatus" : $("[name=estatus] option:selected").val(),
					"mes"     : $("[name=mes] option:selected").val(),
				});
			 }			
		},
    "columns" : [
      { data : 'VtaOper' },
      { data : 'TidCodi' },
      { data : 'VtaNume'  },
      { data : 'VtaFvta' },
      { data : 'cliente_with.PCNomb'},
      { data : 'xml' },
      { data : 'pdf' },
			{ data : 'cdr' },
			{ data : 'estado' },
      { data : 'acciones' },
    ]
  });
}

function subirDocumentos()
{
	if($("[name=mes] option:selected").val() == "todos" ){
		notificaciones('Tiene que seleccionar un mes' , 'error')
		return;		
	}
	if($("#uploadTable td.dataTables_empty" ).length ){
		notificaciones('No ay documentos que subir' , 'error')
		return;		
	}

	desactivar_button(".subir");
	$(".block_elemento").show();

	let funcs = {
		success : (data)=>{
			notificaciones('Se han subido los archivos' , 'success')
			console.log("success" , data );
			table.draw();
		},
		error : (data)=>{
			notificaciones('No se han podido subir los archivos' , 'danger')
			console.log("error" , data );
		},
		complete : function(){
			activar_button(".subir");
			$(".block_elemento").hide();		
		}
	}

	let data = {
		tidcodi : $("[name=tipo] option:selected").val(),
		estatus : $("[name=estatus] option:selected").val(),
		mes     : $("[name=mes] option:selected").val(),
	};

	ajaxs( data , url_upload, funcs  );

}


function uploadIndividual()
{
	desactivar_button(".subir");
	$(".block_elemento").show();

	let funcs = {
		success : (data)=>{
			notificaciones('Se han subido los archivos de este documento' , 'success')
			console.log("success" , data );
			table.draw();
		},
		error : (data)=>{
			notificaciones('No se han podido subir los archivos de este documento' , 'danger')
			console.log("error" , data );
		},
		complete : function(){
			activar_button(".subir");
			$(".block_elemento").hide();		
		}
	}


	let id_documento = $(this).parents('tr').children("td:first-child").text();

	let data = {
		id_documento : id_documento
	};

	ajaxs( data , url_uploadSingle, funcs  );
}


function events(  )
{
	$(".subir").on('click' , subirDocumentos )
	$("[name=tipo],[name=estatus],[name=mes]").on('change',function(){table.draw()});
	$("#uploadTable").on('click', '.uploadSingle' , uploadIndividual );


}

init( events , initDatable );
