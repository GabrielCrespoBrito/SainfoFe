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
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">  
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('css/all-skins.css') }}">  
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
    <a href="http://www.sainfo.pe"> <img src="{{ asset('images/logo.png') }}"> </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" style="overflow: hidden;">
    <p class="login-box-msg">Bienvenido <span class="user_elegir_empresa">{{ auth()->user()->usunomb }}</span>, elija la empresa y el periodo con el que trabajar</p>
    <form action="{{ route('empresa.seleccionada') }}" id="elegirEmpresaPeriodo" method="POST">
      @csrf
      <div class="form-group has-feedback {{ $errors->has('empresa') ? 'has-error' : '' }}">
        <select class="form-control" name="empresa" required autofocus>
          @foreach( $empresas as $empresa )
            @php
              $empresaR =  $empresa->empresa;
              if( $empresaR ){
                if( ! $empresaR->periodos->count() ){
                  continue;
                }
              }
              else {
                continue;
              }
            @endphp
            <option data-periodo="{{ $empresaR->periodos->pluck("Pan_cAnio")->implode(",") }}" {{ $loop->first ? 'selected=selected' : '' }} value="{{ $empresaR->empcodi }}">{{ $empresaR->empcodi }} - {{ $empresaR->EmpNomb }}

            </option>
          @endforeach
        </select>       

      @if ($errors->has('empresa'))
          <span class="invalid-feedback">
              <strong>{{ $errors->first('empresa') }}</strong>
          </span>
      @endif
      
      </div>

      <!-- Periodo -->

      <div class="form-group has-feedback {{ $errors->has('periodo') ? 'has-error' : '' }}">

      <select name="periodo" class="form-control" required>
        @foreach( $empresas->first()->empresa->periodos as $periodo )
          <option {{ $periodo->Pan_cAnio == date('Y') ? 'selected=selected' : '' }} value="{{ $periodo->Pan_cAnio }}"> {{ $periodo->Pan_cAnio }} </option>
        @endforeach
      </select>

      @if ($errors->has('periodo'))
        <span class="invalid-feedback">
          <strong>{{ $errors->first('periodo') }}</strong>
        </span>
      @endif 


      </div>

      <div class="row" style="margin-bottom: 20px">
        <div class="col-xs-6">
          <button type="submit" style="margin-top:0" class="btn btn-primary btn-block btn-group btn-flat"> <span class="fa fa-check"></span> Ingresar</button>
        </div>

        <div class="col-xs-6">
          <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()" class="btn btn-danger btn-group btn-block btn-flat"> <span class="fa fa-power-off"></span> Salir </a>
        </div>          

      </div>


        <!-- /.col -->
      </div>

    </form>

   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="z-index: -5555555;">
      @csrf
    </form> 


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/elegir_empresa/elegir_empresa.js') }}"></script>
<script>
  // $(function(){
  //  $("form").on('submit' , function(e){
  //     $("[type=submit]" ).prop('disabled',true);
  //   });   
  //   $("[name=empresa]").on('change' , function(e){
  //     let dataPeriodos = $("option:selected", this ).attr('data-periodo').split(",");
  //     let selectPeriodo = $("[name=periodo]");
  //     selectPeriodo.empty();
  //     for(let i = 0; i < dataPeriodos.length ; i++){
  //       let periodo = dataPeriodos[i];                
  //       let option = $("<option></option>")
  //       .attr('value', periodo )
  //       .text( periodo );
  //       selectPeriodo.append(option);
  //     }
  //   });
  // });
</script>
</body>
</html>
