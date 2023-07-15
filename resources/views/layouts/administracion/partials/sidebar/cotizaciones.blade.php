<li class="treeview">

  <a href="#">
    <i class="fa fa-list-alt"></i> <span>Pedidos</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">
    <li>
      <a href="{{ route('coti.index', [ 'tipo' => '53']) }}"><i class="fa fa-circle-o"></i> Preventa </a>
    </li>      

    <li>
      <a href="{{ route('coti.index', [ 'tipo' => '50']) }}"><i class="fa fa-circle-o"></i> Cotizaciones 
      </a>
    </li>

    <li>
      <a href="{{ route('coti.index', [ 'tipo' => '98']) }}"><i class="fa fa-circle-o"></i> Orden de Pago </a>
    </li>

    <li>
      <a href="{{ route('tienda.index') }}"><i class="fa fa-circle-o"></i> Tienda </a>
    </li>

  </ul>
</li>
