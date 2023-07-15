<div class="content-wrapper" style="min-height: 1126.3px;">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> @yield('titulo_pagina')
      <small>@yield('titulo_small')</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      @yield('bread')
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <?php 

      $header_box = isset($header_box) ? true : false;

      ?>

      @if( $header_box )
      <div class="box-header with-border">
        <h3 class="box-title"> @yield('titulo_contenido') </h3>
        <div class="box-tools pull-right">
          @yield('box_tools')          
        </div>
      </div>
      @endif

      <div class="box-body">
        @yield('contenido')        
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        @yield('contenido_footer')
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>


