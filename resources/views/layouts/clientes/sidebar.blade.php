<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar" style="height: auto;">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu tree" data-widget="tree">      
      <li class="header">MENÚ NAVEGACION</li>         
        <form id="logout-form" action="{{ route('logoutCliente') }}" method="POST" style="display: none;">
            @csrf
        </form>                 
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>