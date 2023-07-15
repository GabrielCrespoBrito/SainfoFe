@php
$datesEmpresa = get_empresa()->getSuscripcionDates();
$barNotificacion = !$datesEmpresa->isSafeTime;
@endphp


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
  {{-- <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('css/all-skins.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('style.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset(mix('css/all.css')) }}">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/icons/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{  asset('images/icons/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{  asset('images/icons/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{  asset('images/icons/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{  asset('images/icons/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{  asset('images/icons/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{  asset('images/icons/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{  asset('images/icons/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{  asset('images/icons/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="192x192"  href="{{  asset('images/icons/android-icon-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons//favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/icons//favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons//favicon-16x16.png') }}">
  @yield('css')
  @stack('css')
</head>
<?php 
  $collapse = isset($collapse);
?>

<body class="hold-transition skin-blue sidebar-mini {{ $collapse ? 'sidebar-collapse' : '' }} {{ $barNotificacion ? 'bar-noti' : '' }}">

@include('components.bar_important_message')
@include('components.block_elemento', [ 'id' => 'load_screen' , 'className' => 'load_screen' , 'text' => '' ])

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
        <span class="informacion_empresa-ruc"> 
          <span class="codigo_empresa" style="color: white; font-size: .9em"> {{ session()->get('empresa') }} </span>  {{ session()->get('empresa_ruc') }} </span>
       <span class="informacion_empresa-nombre">
        {{ substr( session()->get('empresa_nombre'), 0, 20) }} ...
        </span>
        <span class="informacion_empresa-periodo">{{ session()->get('periodo') }}</span>

        <span class="span-informacion_empresa-local">
          <select class="informacion_empresa-local" data-consult="{{ route('user-local.consult_locals') }}">
          </select>
        </span>
        
      </div>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="fa fa-user fa-1x"></span> <span class="hidden-xs"> {{ auth()->user()->usulogi }} </span>
            </a>

            <ul class="dropdown-menu">
              
              <li class="">          

                <div class="">
                  <a href="{{ route('suscripcion.ordenes.index') }}" class="btn btn-default btn-block btn-flat"> Ordenes </a>
                </div>

                <div class="">
                  <a href="{{ route('suscripcion.suscripcion.info') }}" class="btn btn-default btn-block btn-flat"> Gestionar Plan </a>
                </div>
                

                <div>
                   <a href="{{ route('usuarios.perfil') }}" class="btn btn-default btn-block btn-flat"> Perfil </a>
                </div>


              </li>

              <li class="">                
                <div>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf  </form>                 
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