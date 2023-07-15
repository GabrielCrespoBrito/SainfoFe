<div class="botones row" style="margin-top:30px">

	<!-- left -->
	<div class="col-md-12 col-lg-12 col-sm-12 no_pr">

		<button class="btn btn-primary btn-flat" id="guardarFactura"> <span class="fa fa-save"> </span> Guardar </button>

		<a href="{{ route('home') }}" class="btn btn-danger btn-flat" id="salir"> <span class="fa fa-power-off"> </span> Salir </a>

		@if( ! $empresa->hasDefaultInfo() )
			<a href="{{ route('empresa.informacion_defecto', $empresa->empcodi ) }}" class="btn btn-primary btn-flat pull-right"> <span class="fa fa-info"> </span> Cargar información por defecto </a>
		@endif

	</div>
	<!-- /left -->

</div>