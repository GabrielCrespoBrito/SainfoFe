let H = Helper__;

function successSave(data)
{
	console.log("successSave" , data );

	location.href = $("#salir").attr('href');
}


function addToTable(data)
{
	function motivoColumn(value,index,data,tr){
		// return "<select><option></select>"
		let selectMotivos =  $(".motivos [name=motivos]").clone()[0];

		console.log( "motivo column",  selectMotivos );
		return selectMotivos.outerHTML;
	}

	for (var i = 0; i < data.data.length; i++) {
		let columns = [
			{ name : 'VtaOper' },
			{ name : 'TidCodi' },
			{ name : 'VtaSeri' },
			{ name : 'VtaNumee'},
			{ name : 'VtaImpo' },
			{ name : 'VtaExon' },
			{ name : 'VtaInaf' },
			{ name : 'VtaIGVV' },
			{ name : 'VtaISC' },
			{ name : 'fe_rpta' , render : motivoColumn },
		];

		add_to_table( "#details", data.data , columns );
	}

	console.log( "data", data.data.length , add_to_table );
}

function errorAddItems(data)
{
	let message = data.responseJSON.data;
	notificaciones( message , 'error' );
}


function sendForm(e)
{
	e.preventDefault();

	let table = $("#details");
	let data = {
		ticket : $("[name=ticket]").val(),
		__method : $("[name=_method]").val(),
		_token : $("[name=_token]").val(),
		fecha : $("[name=fecha_documento]").val(),
		items : []
	}

	// contingencia.addItems

	if( table.find('tbody tr').length ){

		let trs = table.find('tbody tr');

		for ( let i = 0; i < trs.length; i++ ) {
			let tr = $(trs[i]);
			let item = {
				vtaoper : tr.find('td:eq(0)').text(),
				motivo : tr.find('[name=motivos] option:selected').val(),
			}

			data.items.push(item)
		}

		let url = $(".form-contingencia").attr('action');

		ajaxs( data, url , { success : successSave } )
	}

	else {
		notificaciones( 'No hay documentos que agregar' , 'error' );
	}

	console.log("Enviando formulario" , data );
}


function addItems(e)
{
	console.log("addItems", e );
	e.preventDefault();

	let url = $(this).data('url');

	ajaxs({}, url , { success : addToTable , error : errorAddItems } )
}




H.add_events(function(){

  $(".datepicker").datepicker({ autoclose: true,  format: 'yyyy-mm-dd' });

  $(".add").on('click', addItems );

  $(".save-form").on('click', sendForm );

})



H.init();