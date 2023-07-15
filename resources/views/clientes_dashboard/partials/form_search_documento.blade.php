<div class="login-box" style="margin-top:0%"> 

  {{-- <div class="login-logo">
    <a href="#"> <img src="{{ asset('images/logo.png') }}"> </a>
  </div> --}}

  <div class="login-box-body" style="padding-bottom:10px">
    <p class="login-box-msg">
    <a class="login_tipo" href="#"> Buscar documentos</a></p>
    
    <form style="#"  action="{{ route('documentos.download') }}" method="POST" enctype="multiform/data">
        @csrf

      <div class="form-group has-feedback {{ $errors->has('tipo_documento') ? 'has-error' : '' }}">
        <select name="tipo_documento" class="form-control">
          <option value="01">Factura</option>
          <option value="03">Boleta</option>
          <option value="07">Nota Credito</option>
          <option value="08">Nota Debito</option>
          <option value="09">Guia remisión</option>          
        </select>
        <span class="fa fa-clone form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback {{ $errors->has('ruc') ? 'has-error' : '' }}">
        <input type="text" name="ruc" value="{{ old('ruc') }}" class="form-control" required placeholder="Ruc">
        <span class="fa  fa-sticky-note-o form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback {{ $errors->has('serie') ? 'has-error' : '' }}">
        <input type="text" name="serie" class="form-control text-uppercase" value="{{ old('serie', '') }}" required placeholder="Serie">
        <span class="fa fa-file-o form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback {{ $errors->has('numero') ? 'has-error' : '' }}">
        <input type="number" name="numero" value="{{ old('numero') }}" class="form-control" required placeholder="Numero">
        <span class="fa fa-sort-numeric-desc form-control-feedback"></span>
      </div>

      <div class="g-recaptcha" data-sitekey="{{ config('app.google_captcha.key') }}"></div>

      

      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12" style="padding-top:10px">
          <button type="submit" class="btn enviar btn-primary btn-block btn-flat"> Buscar </button>
          @if( $errors->has('g-recaptcha-response') )
            <span class="invalid-feedback">{{ $errors->first('g-recaptcha-response')  }}</span>
          @endif
        </div>

        <!-- /.col -->
      </div>

    </form>

  </div>

  <!-- /.login-box-body -->
</div>
