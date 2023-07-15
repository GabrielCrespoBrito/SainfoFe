<li class="treeview">
<a href="#">
  <i class="fa fa fa-gears"></i> <span>Administración</span>
  <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
</a>
<ul class="treeview-menu">

  @can('administracion')

    {{-- PASAR ESTO --}}
    {{-- <li class="treeview">
      <a href="#">
        <i class="fa fa fa-users"></i> <span>Usuarios</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>                       
      <ul class="treeview-menu">
        <li><a href="{{ route('usuarios.mantenimiento') }} "><i class="fa fa-circle-o"></i> Mantenimiento </a></li>          
        <li><a target="_blank" href="{{ route('usuarios_documentos.mantenimiento') }}"><i class="fa fa-circle-o"></i> Documento </a></li>
        <li><a target="_blank" href="{{ route('user-local.index') }}"><i class="fa fa-circle-o"></i> Local </a></li>
      </ul>
    </li> --}}


  {{-- PASAR ESTO  --}}
    {{-- <li class="treeview">
      <a href="#">
        <i class="fa fa fa-users"></i> <span> Suscripciones </span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>                       
      <ul class="treeview-menu">
        <li><a href="{{ route('suscripcion.ordenes.index.admin') }} "><i class="fa fa-circle-o"></i> Ordenes </a></li>          
        <li><a target="_blank" href="#"><i class="fa fa-circle-o"></i> Suscripciones </a></li>
      </ul>
    </li> --}}


  {{-- PASAR ESTO --}}
    {{-- <li class="treeview">        
      <a href="#">
        <i class="fa fa fa-users"></i> <span> Contratos </span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a target="_blank" href="{{ route('contratas.index') }}"><i class="fa fa-circle-o"></i> Contrata </a></li>
        <li><a target="_blank" href="{{ route('contratas.create') }}"><i class="fa fa-circle-o"></i> Generar </a></li>
      </ul>
    </li> --}}
        
    {{-- <li><a target="_blank" href="{{ route('configuracion.index') }}"><i class="fa fa-circle-o"></i> Configuracion </a></li> --}}
    {{-- PASAR ESTE --}}
    {{-- <li><a target="_blank" href="{{ route('empresa.index') }}"><i class="fa fa-circle-o"></i> Empresas </a></li> --}}

    {{-- PASAR ESTA   --}}
    {{-- <li><a target="_blank" href="{{ route('documentos.subir') }}"><i class="fa fa-cloud"></i> Subir Documentos </a></li> --}}
    
    {{-- PASAR ESTE --}}
    {{-- <li><a target="_blank" href="{{ url('busquedaDocumentos') }}"><i class="fa fa-circle-o"></i> Buscar Documentos </a></li> --}}




  @endcan

</ul> 
</li>