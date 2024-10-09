<li class="treeview">
  <a href="#">
    <i class="fa fa-database"></i> <span>Gerencia</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">
  
    <li>
      <a href="{{ route('reportes.utilidades.create') }}"><i class="fa fa-circle-o"></i> Utilidades de Ventas </a>
    </li>

    <li>
      <a href="{{ route('reportes.inventario_valorizado.create') }}"><i class="fa fa-circle-o"></i> Inventario Valorizado </a>
    </li>

    <li>
      <a href="{{ route('reportes.productos_mas_vendidos') }}"><i class="fa fa-circle-o"></i> Productos mas vendidos</a>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span> Vendedores </span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.vendedor_venta.create') }}"><i class="fa fa-circle-o"></i> Ventas </a></li>
        <li><a href="{{ route('reportes.vendedor_producto.create') }}"><i class="fa fa-circle-o"></i> Productos </a></li>
        <li><a href="{{ route('reportes.vendedor_estadistica.create') }}"><i class="fa fa-circle-o"></i> Estadisticas </a></li>
        <li><a href="{{ route('reportes.vendedor_venta_productos.create') }}"><i class="fa fa-circle-o"></i> Ventas-Productos </a></li>
        <li><a href="{{ route('reportes.vendedor_cliente.create') }}"><i class="fa fa-circle-o"></i> Clientes  </a></li>
        <li><a href="{{ route('reportes.vendedor_cobertura.create') }}"><i class="fa fa-circle-o"></i> Coberturas  </a></li>

      </ul>
    </li>

    <li>
      <a href="{{ route('reportes.mejores_clientes') }}"><i class="fa fa-circle-o"></i> Mejores clientes </a>
    </li>

    <li>
      <a href="{{ route('reportes.ventas_anual') }}"><i class="fa fa-circle-o"></i> Ventas Anual </a>
    </li>

    {{-- <li> --}}
      {{-- <a href="{{ route('reportes.util-ventas') }}"><i class="fa fa-circle-o"></i> Utilidades de ventas </a> --}}
    {{-- </li> --}}

  </ul>
</li>