<li class="treeview">
  <a href="#">
    <i class="fa fa-building-o"></i> <span> Empresas </span>
    <span class="pull-right-container">
      <i class="fa fa-angle-right pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">  
    <li><a href="{{ route('admin.empresa.index') }}"><i class="fa fa-building-o"></i> Empresas </a></li>
    <li><a href="{{ route('admin.documentos.index') }}"><i class="fa fa-file-text-o"></i> Documentos </a></li>
    <li><a href="{{ route('admin.guias.index') }}"><i class="fa fa-truck"></i> Guias </a></li>
    <li><a href="{{ route('admin.resumenes.index') }}"><i class="fa fa-file-o"></i> Resumenes </a></li>
  </ul>
</li> 

<li class="treeview">
  <a href="#">
    <i class="fa fa-users"></i> <span> Usuarios </span>
    <span class="pull-right-container">
      <i class="fa fa-angle-right pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">
    <li><a href="{{ route('admin.usuarios.index') }}"><i class="fa fa-users"></i> Usuarios </a></li>
    <li><a href="{{ route('admin.user-local.index') }}"><i class="fa fa-circle-o"></i> Usuario-Local </a></li>
    <li><a href="{{ route('admin.usuarios_documentos.mantenimiento') }}"><i class="fa fa-circle-o"></i> Usuario-Docu </a></li>
  </ul>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-circle-o"></i> <span> Pendientes </span>
    <span class="pull-right-container">
      <i class="fa fa-angle-right pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">
    <li><a href="{{ route('admin.documentos.pending') }}"><i class="fa fa-file-text-o"></i> Documentos </a></li>
    <li><a href="{{ route('admin.guias.pending') }}"><i class="fa fa-truck"></i> Guias </a></li>
    <li><a href="{{ route('admin.resumenes.pending') }}"><i class="fa fa-file-o"></i> Resumenes </a></li>
  </ul>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-circle-o"></i> <span> Sistema </span>
    <span class="pull-right-container">
      <i class="fa fa-angle-right pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">
    <li><a href="{{ route('admin.config.index') }}"><i class="fa fa-circle-o"></i> Configuración </a></li>    
    <li><a target="_blank" href="{{ route('admin.suscripcion.ordenes.index') }}"><i class="fa fa-circle-o"></i> Ordenes </a></li>    
    <li><a target="_blank" href="{{ route('admin.tipo_pago.index') }}"><i class="fa fa-circle-o"></i> Tipos de Pago </a></li>
    <li><a target="_blank" href="{{ route('admin.plan.index') }}"><i class="fa fa-circle-o"></i> Planes </a></li>
    <li><a target="_blank" href="{{ route('admin.permissions.index') }}"><i class="fa fa-circle-o"></i> Permisos </a></li>
    <li><a target="_blank" href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> Roles </a></li>
    <li><a href="{{ route('admin.consultar_doc.index') }}"><i class="fa fa-circle-o"></i> Consultar Docs </a></li>
  </ul>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-circle-o"></i> <span> Pagina </span>
    <span class="pull-right-container">
      <i class="fa fa-angle-right pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu">

    <li><a href="{{ route('admin.pagina.clientes.index') }}"><i class="fa fa-circle-o"></i> Clientes </a></li>  

    <li><a href="{{ route('admin.pagina.banners.index') }}"><i class="fa fa-circle-o"></i> Banners </a></li>  

    <li><a href="{{ route('admin.pagina.testimonios.index') }}"><i class="fa fa-circle-o"></i> Testimonios </a></li>  

    <li><a href="{{ route('admin.pagina.contabilidad-caracteristica.index') }}"><i class="fa fa-circle-o"></i> Cont-Caracteristicas </a></li>  
      
    <li><a href="{{ route('admin.pagina.contabilidad-testi.index') }}"><i class="fa fa-circle-o"></i> Cont-Testimonios </a></li>  

  </ul>
</li>
