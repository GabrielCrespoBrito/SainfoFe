<div class="login-box"> 

  <div class="login-box-body">
    <p class="login-box-msg">
    <a class="login_tipo" href="#"> Ingresar administración de documentos</a></p>
    
      @if( $errors->count )
        @foreach( $errors->all() as $error )
          {{ $error }}
        @endforeach

      @endif


      <form id="cliente_form" action="{{ route('login_cliente') }}" method="POST">      
          @csrf



        @if( is_online() )
        <div class="form-group has-feedback {{ $errors->has('ruc_empresa') ? 'has-error' : '' }}">
          <input type="text" value="{{ old('ruc_empresa') }}" name="ruc_empresa" class="form-control" placeholder="Ruc Empresa" required autofocus>
          <span class="fa fa-user form-control-feedback"></span>
          
          @if ($errors->has('ruc_empresa'))
              <span class="invalid-feedback">
                  <strong>{{ $errors->first('ruc_empresa') }}</strong>
              </span>
          @endif
        </div>
        @endif


        <div class="form-group has-feedback {{ $errors->has('documento') ? 'has-error' : '' }}">
          <input type="text" value="{{ old('documento') }}" name="documento" class="form-control" placeholder="Ruc cliente" required autofocus>
          <span class="fa fa-user form-control-feedback"></span>
          
          @if ($errors->has('documento'))
              <span class="invalid-feedback">
                  <strong>{{ $errors->first('documento') }}</strong>
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

        <br><br>


        <div class="row">
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn enviar btn-primary btn-block btn-flat enviar">Ingresar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

  </div>

  <!-- /.login-box-body -->
</div>
