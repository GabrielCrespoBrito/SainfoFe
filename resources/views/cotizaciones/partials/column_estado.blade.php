@if( $cotesta == 'P' )
	<span class="btn btn-xs btn-default"> Pendiente </span>
@elseif( $cotesta == 'F' )
	<span class="btn btn-xs btn-primary"> Facturado </span>
@elseif( $cotesta == 'A' )
	<span class="btn btn-xs btn-warning"> Anulado </span>
@endif