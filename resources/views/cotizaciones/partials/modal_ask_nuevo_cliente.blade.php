<div class="modal fade" id="modalNuevoCliente">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" >&times;</button>
				<h4 class="modal-title">No exise este cliente</h4>
			</div>
			<div class="modal-body">
				<p> No existe este cliente, desea registrarlo?</p>
			</div>
			<div class="modal-footer">
				
				<a target="_blank" data-url="{{ route('clientes.index' , [ 'create' , 'XXX' ]) }}" id="nuevocliente" href="{{ route('clientes.index' , [ 'create' , 'XXX' ]) }}" class="btn btn-primary"> Aceptar </a>

				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>

			</div>
		</div>
	</div>
</div>