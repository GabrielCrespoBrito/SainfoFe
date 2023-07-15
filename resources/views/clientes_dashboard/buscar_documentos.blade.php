<!DOCTYPE html>
<html>
<head>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SAINFO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('css/all-skins.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
  @if( session()->has('notificacion') )
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">  
  @endif
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page" style="background: #ccc url('images/background.jpg') no-repeat;">

<div class="row">

<div class="col-md-12">
    <div class="login-logo">
    <a href="#"> <img src="{{ asset('images/logo.png') }}"> </a>
  </div>
</div> 

@php
  $area_cliente = config('app.cliente_area_adm_docs');
@endphp

@if($area_cliente)
<div class="col-md-6"> 
  @include('clientes_dashboard.partials.form_cliente')
</div> 
@endif

<div class="{{ $area_cliente ? 'col-md-6' : 'col-md-12' }}">
  @include('clientes_dashboard.partials.form_search_documento')
</div>


</div>
{{-- abcdef --}}


<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>

@if( session()->has('notificacion') )

<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">  

<script src="{{ asset('js/toastr.min.js') }}"></script>

    <script type="text/javascript">

      $(function(){

        console.log("aa"); 

        $.toast({
          heading   : "{{ session()->get('titulo')  }}" ,
          text      : "{{ session()->get('mensaje') }}",
          position  : 'top-center',
          showHideTransition : "showHideTransition", 
          hideAfter : 3000,
          icon      : "{{ session()->get('tipo') }}",
        });
      });
    </script>
@endif

<script>
  $(function () {

   $("#cliente_form , #erp_form").on('submit' , function(e){
      $(".enviar" ).prop('disabled',true);
    });

    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });

    $(".login_tipo").click(function(e){

      e.preventDefault();

      let $this = $(this);      

      if(!$this.is('.active')){

        $(".login_tipo").removeClass('active')
        $this.addClass('active');      

        $("form").hide(400);
        $("#"+$this.data('form')).show();
      }
    })

  });
</script>
</body>
</html>
