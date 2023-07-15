@php
  $modulo_canje_nv = $empresa->getDataAditional('modulo_canje_nv');
  $modulo_manejo_stock = $empresa->getDataAditional('modulo_manejo_stock');
  $modulo_precio_unico = $empresa->getDataAditional('modulo_precio_unico');
  $modulo_restriccion_venta_por_stock = $empresa->getDataAditional('modulo_restriccion_venta_por_stock');
  
@endphp

<div class="empresa-parametros">

  <form action="{{ $routeModulo }}" method="post">
    <div class="row">
      <div class="col-md-12">
        <p class="title-parametros"> <span class="fa fa-bookmark"></span> Activación/Desactivación de Modulos </p>
      </div>
    </div>

    {{ csrf_field() }}

    <div class="row">
    
    <div class="form-group col-md-3">
      <label for="modulo_canje_nv" class="oneline"> <input id="modulo_canje_nv" value="1" {{ $modulo_canje_nv ? 'checked=checked' : '' }} type="checkbox" name="modulo_canje_nv" />  Canje de Notas de Ventas   </label>
    </div>

    <div class="form-group col-md-3">
      <label for="modulo_manejo_stock" class="oneline"> <input id="modulo_manejo_stock" value="1" {{ $modulo_manejo_stock ? 'checked=checked' : '' }} type="checkbox" name="modulo_manejo_stock" />  Manejo de Stocks  </label>
    </div>

    <div class="form-group col-md-3">
      <label for="modulo_restriccion_venta_por_stock" class="oneline"> <input id="modulo_restriccion_venta_por_stock" value="1" {{ $modulo_restriccion_venta_por_stock ? 'checked=checked' : '' }} type="checkbox" name="modulo_restriccion_venta_por_stock" /> Restringir Por Falta de Stock  </label>
    </div>

    <div class="form-group col-md-3">
      <label for="modulo_precio_unico" class="oneline"> <input id="modulo_precio_unico" value="1" {{ $modulo_precio_unico ? 'checked=checked' : '' }} type="checkbox" name="modulo_precio_unico" />  Precio Unico  </label>
    </div>


    </div>    


    <div class="row">
      <div class="col-md-12 col-lg-12 col-sm-12 no_pr">
        <button class="btn btn-primary btn-flat" id="guardarFactura">
          <span class="fa fa-save"> </span> Guardar
        </button>
      </div>
    </div>

  </form>
</div>