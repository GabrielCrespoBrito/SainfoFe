<li class="treeview">
    <a href="#">
      <i class="fa fa-folder"></i> <span>Ventas</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="{{ route('ventas.index') }}"><i class="fa fa-circle-o"></i> Ventas </a></li>
        <li><a href="{{ route('ventas.pendientes') }}"><i class="fa fa-circle-o"></i> Pendientes </a></li>
        <li><a href="{{ route('boletas.resumen_dia') }}"><i class="fa fa-circle-o"></i> Resumenes de boletas </a></li>
        <li><a href="{{ route('contingencia.index') }}"><i class="fa fa-circle-o"></i> Resumenes de contingencia </a></li>
        {{-- <li><a href="{{ route('reportes.consultar_documentos') }}"><i class="fa fa-circle-o"></i> Consultar documentos </a></li> --}}
    </ul>
  </li>