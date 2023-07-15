<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    @yield('title','SAINFO')
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
  <!-- Toasts -->
  <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">  
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('css/all-skins.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  @yield('css')
  @stack('css')
  <!-- Google Font -->
  @if( return_false("Desabilitar temporadalmente la carga de esto") )
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @endif
</head>

<?php 
  $collapse = isset($collapse);
?>
<body class="hold-transition {{ $collapse ? 'sidebar-collapse' : '' }}  skin-blue sidebar-mini">  

<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ route('home')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</b>IN</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SAINFO</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Menu navigación</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="informacion_empresa">
        <span class="informacion_empresa-ruc"> {{ session()->get('empresa_ruc') }}</span>
       <span class="informacion_empresa-nombre">
        {{ substr( session()->get('empresa_nombre'), 0, 20) }} ...
        </span>
        <span class="informacion_empresa-periodo">{{ session()->get('periodo') }}</span> 
      </div>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        @include('layouts.administracion.partials.notificaciones_menu')

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="fa fa-user fa-1x"></span>
              <span class="hidden-xs"> {{ auth()->user()->usulogi   }} </span>
            </a>
            <ul class="dropdown-menu">

              <li class="user-footer">                
                <div>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>                 
                  <a href="#" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="btn btn-default btn-block btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>