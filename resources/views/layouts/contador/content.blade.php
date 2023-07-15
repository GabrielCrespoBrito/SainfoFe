<div class="content-wrapper" style="min-height: 1126.3px;">
  <!-- Content Header (Page header) -->
  <section class="content-header">    
    <h1> @yield('titulo_pagina')
      <small>@yield('titulo_small')</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      @yield('bread')
    </ol>
  </section>

  <!-- Main content -->
  <section class="content {{ isset($class_adicional) ? $class_adicional : '' }} ">
    <!-- Default box -->
    <div class="box">
      <?php $header_box = isset($header_box) ? true : false  ?>

      <div class="box-body">
        @yield('contenido')        
      </div>
      <!-- /.box-body -->
      @yield('contenido_footer')

      <div class="box-footer">
          <div class="pull-right hidden-xs"><b>Version</b> 1.0</div>
          <strong>Copyright © 2014-{{ date('Y') }} <a target="_blank" href="{{ get_setting('website_sainfo' , '#')  }}">Sainfo</a></strong> Todos los derechos reservados
      </div>


      <!-- /.box-footer-->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->


</div>



