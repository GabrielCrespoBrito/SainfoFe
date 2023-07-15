<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones  <span class="caret"></span>
  </button>
  <ul class="dropdown-menu sainfo">
    <li><a class="modificar_elemento" href="#"> Modificar </a></li>
    <li><a class="modificar_elemento movimiento_elemento" href="#"> Movimientos </a></li>
    <li><a class="eliminar_elemento" class="bg-red" href="#"> Eliminar </a></li>
    <li><a class="" target="_blank" href="{{ route('unidad.index', $model->ProCodi ) }}"> Precios </a></li>
    <li><a class="" target="_blank" href="{{ route('productos.unidad.mantenimiento', $ID ) }}"> Menudeo </a></li>
    <li><a class="" target="_blank" href="{{ route('reportes.compra_venta', ['producto' => $ProCodi ]  ) }}"> Compra/Venta </a></li>
  </ul>
</div>