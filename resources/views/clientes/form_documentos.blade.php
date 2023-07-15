<!DOCTYPE html>
<html>
<head>
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
  @if(session()->has('notificacion'))
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
<div class="login-box"> 
  <div class="login-logo">
    <a href="#"> <img src="{{ asset('images/logo.png') }}"> </a>
  </div>
  <!-- /.login-logo -->
    <?php 
      $form_1 = $errors->has('usulogi') || $errors->has('password');
      $form_2 = $errors->has('ruc') || $errors->has('password_cliente');
    ?>

  <div class="login-box-body">
    <p class="login-box-msg">


      <a data-form="erp_form" class="login_tipo {{ $form_2 ? '' : 'active'}}" href="#">
      EPR</a>

      <a data-form="cliente_form" class="login_tipo {{ $form_2 ? 'active' : ''}}" href="#">
      Clientes</a>
    </p>
    
    <form style="display:{{ $form_2 ? 'none' : 'block'}}" action="{{ route('login') }}" id="erp_form" method="POST">
        @csrf
      <div class="form-group has-feedback {{ $errors->has('usulogi') ? 'has-error' : '' }}">
        <input type="text" value="{{ old('usulogi') }}" name="usulogi" class="form-control" placeholder="Usuario" required autofocus>
        <span class="fa fa-user form-control-feedback"></span>
        
        @if ($errors->has('usulogi'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('usulogi') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
        <input type="password" name="password" class="form-control" required placeholder="Contraseña">
        <span class="fa fa-lock form-control-feedback"></span>

        @if ($errors->has('password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label> <input type="checkbox">Recordarme</label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn enviar btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    
    <form style="display:{{ $form_2 ? 'block' : 'none'}}" id="cliente_form" action="{{ route('login_cliente') }}" method="POST">      
        @csrf
      <div class="form-group has-feedback {{ $errors->has('ruc') ? 'has-error' : '' }}">
        <input type="text" value="{{ old('ruc') }}" name="ruc" class="form-control" placeholder="RUC" required autofocus>
        <span class="fa fa-user form-control-feedback"></span>
        
        @if ($errors->has('ruc'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('ruc') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group has-feedback {{ $errors->has('password_cliente') ? 'has-error' : '' }}">
        <input type="password" name="password_cliente" class="form-control" required placeholder="Contraseña">
        <span class="fa fa-lock form-control-feedback"></span>

        @if ($errors->has('password_cliente'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password_cliente') }}</strong>
            </span>
        @endif

      </div>

      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn enviar btn-primary btn-block btn-flat enviar">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="#">Olvidé mi contraseña</a><br>

  </div>
  <!-- /.login-box-body -->
</div>
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
