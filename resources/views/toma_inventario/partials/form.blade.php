@php
	$create = $accion == "create";
	$edit 	= $accion == "edit";
	$show 	= $accion == "show";
	$active_form = $create || $edit;
@endphp

<form 
  class="form_principal factura_div focus-green"
  method="post"
  id="form-toma"
  action="{{ $route }}">

  @csrf

	@include('toma_inventario.partials.form.info') 
  <hr>
	@include('toma_inventario.partials.form.table', [ 'create' => $create ])
  <hr>
	@include('toma_inventario.partials.form.botones' , compact('create','edit','show','active_form') )

</form>


