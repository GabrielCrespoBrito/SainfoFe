<li class="treeview">
    <a href="#">
      <i class="fa fa-folder"></i> <span>Compras</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{ route('compras.index') }}"><i class="fa fa-circle-o"></i> Compra </a></li>
        <li><a href="{{ route('orden_compras.index') }}"><i class="fa fa-circle-o"></i>  Orden de Compra </a></li>
    </ul>
  </li>