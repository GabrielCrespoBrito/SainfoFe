@php
	$create = $accion == "create";
	$edit 	= $accion == "edit";
	$show 	= $accion == "show";
	$active_form 	= $create || $edit;
	$url = $create ? route('produccion.store') : route('produccion.update', $produccion->manId );
@endphp

@include('produccion.partials.form.producto_plantilla', [
  'plantilla' => true,
  'deleteBtn' => true,   
  'nameInputProducto' => 'producto_insumo_id[]', 
  'nameInputCantidad' => 'producto_insumo_cantidad[]'])

<form class="form_principal factura_div focus-green" method="post" action="{{ $url }}" id="form_principal">		

  @if($edit)
    @method('put')
  @endif

  @include('produccion.partials.form.nroventa', compact('create','edit','show', 'active_form'))

	@include('produccion.partials.form.descripcion', compact('create','edit','show', 'active_form')) 

  <hr/>
  @include('produccion.partials.form.producto_final', compact('create','edit','show', 'active_form')) 

  <hr/>
  @include('produccion.partials.form.producto_insumos', compact('create','edit','show', 'active_form')) 

  <div class="row pt-x10">
    <div class="col-md-12">
    @if($show)
        <a href="{{ route('produccion.edit', $produccion->manId) }}" class="btn btn-flat btn-primary">Modificar</a>

    @else
        <button class="btn btn-flat btn-primary" type="submit"> Guardar </button>
    @endif
        <a href="{{ route('produccion.index') }}" class="btn btn-flat btn-danger pull-right salir-btn">Salir</a>
    </div>
    
  </div>
</form>
