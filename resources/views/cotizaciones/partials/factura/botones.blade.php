<div class="botones row">

	<!-- left -->
	<div class="col-md-8 col-lg-8 col-sm-12 no_pr">

		<div class="pull-left">

			<a href="#" class="btn btn-primary btn-flat" id="guardarFactura" data-toggle="tooltip" title="Grabar">
				<span class="fa fa-save"></span> 
			</a>

			<a href="#" class="btn btn-default btn-flat condi_venta" data-toggle="tooltip"  title="Condición de Cotizacion">
				<span class="fa fa-file-text-o"></span> 
			</a>


			<a href="{{ $create ? '#' : $cotizacion->getRouteImprimir() }}"
        target="_blank" 
        class="btn {{ $create ? 'disabled' : ''  }} btn-default btn-flat imprimir">	<span class="fa fa-print"></span> Imprimir </a>

			<a 
			href="#"
			id="salir_"
			{{-- data-href="{{ route('coti.index', [ 'tipo' => $tipo ]) }}" --}}
			data-href="{{ $routeIndex }}"
			class="btn btn-danger btn-flat"
			data-toggle="tooltip"
			title="Salir">
				<span class="fa fa-power-off"></span>
			</a>

  	</div>

	</div>
	<!-- /left -->


	<!-- right -->
	<div class="col-md-4 col-lg-4 col-sm-12">


    @if($create && $importHabilitado )
    <a 
    href="#"
    data-toggle="modal"
    data-target="#modalImportTienda"
    id="import-tienda"
    style="margin-left: 10px"
    class="btn btn-default btn-flat pull-right">
      <span class="fa fa-download"></span> Tienda 
    </a>
    @endif

    @if($import)
    <a 
    href="#"
    data-toggle="modal"
    data-info="{{ json_encode($importInfo['woocomerce_data']) }}"
    data-target="#modalShowOrden"
    data-target-container="#order-show-container"
    class="btn btn-primary btn-flat pull-right btn-show-order">
      Cotizacion Tienda #{{ $importInfo['id'] }} 
    </a>
    @endif

		<div class="pull-right">
	    <div class="form-group">  
	    	@if($create )
		      <div class="input-group">
		        <input type="hidden" name="tipo" value="{{ $tipo }}">
		      </div>
	    	@endif
	    </div>
		</div>
	</div>
</div>