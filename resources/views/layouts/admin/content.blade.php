@php
  $box_transparent = $box_transparent ?? false;    

@endphp

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
  <div class="box {{ $box_transparent ? 'box-transparent' : '' }}">
      <?php $header_box = isset($header_box) ? true : false;   ?>

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



