<div class="row">   
  <div class="form-group col-md-12">  
    <p class="text-center">Producto Final</p>
  </div>
</div>

@if($show)

@include('produccion.partials.form.producto_plantilla_show', [
  'producto' =>  $produccion->manNomb, 
  'cantidad' => $produccion->manCant
  ])

@else

@include('produccion.partials.form.producto_plantilla', [
  'nameInputProducto' => 'producto_final_id', 
  'nameInputCantidad' => 'producto_final_cantidad',
  'id' => $produccion->manCodi,
  'text' => $produccion->manNomb,
  'cantidad' => $produccion->manCant,
  ])

@endif