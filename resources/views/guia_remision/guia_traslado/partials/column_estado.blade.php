@if( $GuiEsta == "A" )

<a href="#" class="btn btn-xs anulada">
  <span class="fa fa-spin"></span>
  Anulada
</a>

@else

	@if( $fe_rpta == 9 )

	<a href="#" class="btn btn-xs por_enviar">
	  <span class="fa fa-spin fa-spinner"></span>
	  Por enviar  
	</a>

	@elseif( $fe_rpta == 0 )

	<a href="#" class="btn btn-xs aceptado">
	  <span class="fa fa-check"></span>Aceptado  
	</a>

	@endif

@endif
