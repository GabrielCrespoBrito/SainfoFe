<li class="treeview">
  <a href="#">
    <i class="fa fa fa-cube"></i> <span>Productos</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">  
    <li><a href="{{ route('productos.index') }}"><i class="fa fa-circle-o"></i> Productos </a></li>                    
    <li><a href="{{ route('grupos.index') }}"><i class="fa fa-circle-o"></i> Grupos </a></li>
    <li><a href="{{ route('familias.index') }}"><i class="fa fa-circle-o"></i> Familia </a></li>
    <li><a href="{{ route('marcas.index') }}"><i class="fa fa-circle-o"></i> Marcas </a></li>
    <li><a href="{{ route('listaprecio.index') }}"><i class="fa fa-circle-o"></i> Lista de precios </a></li>
    <li><a href="{{ route('unidad.index') }}"><i class="fa fa-circle-o"></i> Unidades </a></li>
  </ul>
</li>