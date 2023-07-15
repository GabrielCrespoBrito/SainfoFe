<li class="treeview">
  <a href="#">
    <i class="fa fa-file-pdf-o"></i> <span>Reportes</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
  
    <!-- Multinivel -->
    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span>Artículos</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>

      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.compra_venta') }}"><i class="fa fa-circle-o"></i> Compra y venta</a></li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span>Fact Electr.</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.facturacion_electronica') }}"><i class="fa fa-circle-o"></i> Fact Electr.</a></li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span>Bancos y Caja</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.ventas_tipopago.create') }}"><i class="fa fa-circle-o"></i> Tipos Pagos</a></li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span>Almacenes</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.kardex_fisico') }}"><i class="fa fa-circle-o"></i> Kardex Fisico</a></li>
        <li><a href="{{ route('reportes.kardex_valorizado') }}"><i class="fa fa-circle-o"></i> Kardex Valorizado</a></li>
        <li><a href="{{ route('reportes.kardex_by_date') }}"><i class="fa fa-circle-o"></i> Kardex Por Fecha</a></li>
        <li><a href="{{ route('reportes.kardex_traslado') }}"><i class="fa fa-circle-o"></i> Kardex Traslado Almacenes</a></li>
        <li><a href="{{ route('reportes.guias') }}"><i class="fa fa-circle-o"></i> Guias Electronica</a></li>
        <li><a href="{{ route('reportes.productos_stock.create') }}"><i class="fa fa-circle-o"></i> Producto Stock</a></li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-circle-o"></i>
        <span> Registro Contable </span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('reportes.ventas_mensual') }}"><i class="fa fa-circle-o"></i> Ventas </a></li>
        <li><a href="{{ route('reportes.compras_mensual.create') }}"><i class="fa fa-circle-o"></i> Compras </a></li>
      </ul>
    </li>
    
    <li><a href="{{ route('reportes.ventas') }}"><i class="fa fa-circle-o"></i> Ventas</a></li>
    <li><a href="{{ route('reportes.compras') }}"><i class="fa fa-circle-o"></i> Compras </a></li>
    <li><a href="{{ route('reportes.detraccion.create') }}"><i class="fa fa-circle-o"></i> Detracción </a></li>
    <li><a href="{{ route('reportes.listaprecio.show') }}"><i class="fa fa-circle-o"></i> Lista Precios </a> </li>
    <li><a href="{{ route('guia.reporte') }}"><i class="fa fa-circle-o"></i> Guia </a></li>
    <li><a href="{{ route('reportes.entidad') }}"><i class="fa fa-circle-o"></i> Clientes </a></li>    
    <li><a href="{{ route('reportes.clientes') }}"><i class="fa fa-circle-o"></i> Clientes Deudas </a></li>

    @if( auth()->user()->isAdmin() )
      <li><a href="{{ route('reportes.validate_documentos_mensual') }}"><i class="fa fa-circle-o"></i> Validar Documentos Sunat </a></li>
    @endif

  </ul>
</li>