@php
  $modulo_canje_nv = $empresa->getDataAditional('modulo_canje_nv');
  $modulo_manejo_stock = $empresa->getDataAditional('modulo_manejo_stock');
  $modulo_precio_unico = $empresa->getDataAditional('modulo_precio_unico');
  $modulo_produccion_manual = $empresa->getDataAditional('modulo_produccion_manual');
  $modulo_restriccion_venta_por_stock = $empresa->getDataAditional('modulo_restriccion_venta_por_stock');
  $modulo_venta_rapida = $empresa->getDataAditional('modulo_venta_rapida');
  $no_actualizar_costo_por_compra = $empresa->getDataAditional('no_actualizar_costo_por_compra');
  
  
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
    
    <div class="form-group col-md-2">
      <label for="modulo_canje_nv" class="oneline"> <input id="modulo_canje_nv" value="1" {{ $modulo_canje_nv ? 'checked=checked' : '' }} type="checkbox" name="modulo_canje_nv" />  Canje de Notas de Ventas   </label>
    </div>

    <div class="form-group col-md-2">
      <label for="modulo_manejo_stock" class="oneline"> <input id="modulo_manejo_stock" value="1" {{ $modulo_manejo_stock ? 'checked=checked' : '' }} type="checkbox" name="modulo_manejo_stock" />  Manejo de Stocks  </label>
    </div>

    <div class="form-group col-md-2">
      <label for="modulo_restriccion_venta_por_stock" class="oneline"> <input id="modulo_restriccion_venta_por_stock" value="1" {{ $modulo_restriccion_venta_por_stock ? 'checked=checked' : '' }} type="checkbox" name="modulo_restriccion_venta_por_stock" /> Restringir Por Falta de Stock  </label>
    </div>

    <div class="form-group col-md-2">
      <label for="modulo_precio_unico" class="oneline"> <input id="modulo_precio_unico" value="1" {{ $modulo_precio_unico ? 'checked=checked' : '' }} type="checkbox" name="modulo_precio_unico" />  Precio Unico  </label>
    </div>

    <div class="form-group col-md-2">
      <label for="modulo_produccion_manual" class="oneline"> <input id="modulo_produccion_manual" value="1" {{ $modulo_produccion_manual ? 'checked=checked' : '' }} type="checkbox" name="modulo_produccion_manual" />  Producción Manual  </label>
    </div>

    <div class="form-group col-md-2">
      <label for="modulo_venta_rapida" class="oneline"> <input id="modulo_venta_rapida" value="1" {{ $modulo_venta_rapida ? 'checked=checked' : '' }} type="checkbox" name="modulo_venta_rapida" />  Venta Rapida  </label>
    </div>


    <div class="form-group col-md-2">
      <label for="no_actualizar_costo_por_compra" class="oneline"> <input id="no_actualizar_costo_por_compra" value="1" {{ $no_actualizar_costo_por_compra ? 'checked=checked' : '' }} type="checkbox" name="no_actualizar_costo_por_compra" />  No Actualizar Costos por Compra  </label>
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