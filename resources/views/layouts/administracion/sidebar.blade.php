<aside class="main-sidebar">
  <!-- sidebar -->  
  <section class="sidebar" style="height: auto;">
    <ul class="sidebar-menu tree" data-widget="tree">
      <li class="header">MENÚ NAVEGACION</li>

      @include('layouts.administracion.partials.sidebar.ventas')
      
      {{-- @can('show guia') --}}
      @include('layouts.administracion.partials.sidebar.almacen')
      {{-- @endcan --}}

      @include('layouts.administracion.partials.sidebar.tienda')
      
      @include('layouts.administracion.partials.sidebar.compras')

      @include('layouts.administracion.partials.sidebar.cotizaciones')
      
      @include('layouts.administracion.partials.sidebar.productos')

      {{-- Reporte  --}}
      @include('layouts.administracion.partials.sidebar.reportes')
      @include('layouts.administracion.partials.sidebar.gerencia')      
      
      @include('layouts.administracion.partials.sidebar.cajas')
      @include('layouts.administracion.partials.sidebar.banco')
      
      @include('layouts.administracion.partials.sidebar.clientes')
            
      {{-- @can('utilitarios') --}}
        @include('layouts.administracion.partials.sidebar.utilitarios')
      {{-- @endcan --}}
      
      {{-- @include('layouts.administracion.partials.sidebar.administracion')       --}}

      <li class="treeview treeview-logout">
      <a href="#" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <i class="fa fa-sign-out"></i> <span>Salir</span>     </a>
      </li>

      {{-- <a href="#" onclick="event.preventDefault(); --}}
        {{-- // document.getElementById('logout-form').submit();" class="btn btn-default btn-block btn-flat">Salir</a> --}}



    </ul>
  </section>
  <!-- /.sidebar -->
</aside>