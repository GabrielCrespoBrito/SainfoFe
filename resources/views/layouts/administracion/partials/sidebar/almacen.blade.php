<li class="treeview">
  <a href="#">
    <i class="fa fa-truck"></i> <span>Almacen</span>
    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
  </a>
  <ul class="treeview-menu">

    <li><a href="{{ route('guia.index', ['format' => false ] ) }}"><i class="fa fa-long-arrow-right"></i> Guia remisión </a></li>

    <li><a href="{{ route('guia_ingreso.index' , ['format' => false ] ) }}"><i class="fa  fa-long-arrow-left"></i> Guia ingreso </a></li>

    <li><a href="{{ route('guia_transportista.index' , ['format' => false ] ) }}"><i class="fa fa-truck"></i> Guia Transportista </a></li>

    <li><a href="{{ route('guia.pendientes') }}"><i class="fa fa-circle-o"></i> Pendientes </a></li>
    
    <li><a href="{{ route('guia_traslado.index' , ['format' => false ] ) }}"><i class="fa fa-exchange"></i> Traslados </a></li>

    <li><a href="{{ route('toma_inventario.index') }}"><i class="fa fa-list-alt"></i> Toma de inventario </a></li>

  </ul> 
</li>