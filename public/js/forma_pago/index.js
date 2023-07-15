console.log(
	"aaa",
	"forma_pago"
);

$(document).ready(function (e) {


	$('.delete-btn').on('click', function (e) {
		e.preventDefault();
		if (confirm("¿Esta seguro que desea eliminar este tipo de pago?")) {
			let $form = $(".form-delete-forma-pago");

			let route = $form.attr('data-route').replace('@@', $(this).attr('data-id') )
			console.log( route );
			$form.attr( 'action', route );		
			$form.submit();
		}
	})



})