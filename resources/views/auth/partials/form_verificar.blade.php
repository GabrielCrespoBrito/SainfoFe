@php
  $messageNumberInput = $hasNumberSave ?  "Número de telefono agregado" : "Ingresa tú número de telefono"; 
@endphp

<form method="POST" id="form-verificar" action="{{ route('usuario.store_phone') }}">

  <div class="step number-phone">
    <p class="texto-step {{ $hasNumberSave ? 'number-save' : '' }}"> {{ $messageNumberInput }} <span class="fa fa-{{ $hasNumberSave ? 'check' : 'phone' }}"> </span>   </p>

    <div class="input-group input-group-sm set-number {{ $hasNumberSave ? 'hide' : 'showTable' }}">
      <input
      type="text"
      value="{{ $number }}"
      class="form-control"
      maxlength="9"
      name="guardar">
      <span class="input-group-btn">
        <button type="button" class="save-number btn btn-info btn-block text-center btn-flat"> <span class="fa fa-save"> </span> Guardar </button>
      </span>
    </div>

    {{-- Mostrar numero --}}
    <div class="form-group has-feedback   show-number {{ $hasNumberSave ? 'show' : 'hide'}}">
      <p class="form-control"> {{ $number }} </p>     
      <span class="fa fa-phone form-control-feedback"></span>
    </div>
    @if(!$hasNumberSave)
      @include('partials.button_logout', ['clases' => 'pull-left' ])
    @endif
  </div>


  <div class="step verification-code {{ $hasNumberSave ? '' : 'hide' }}">

    <p class="texto-step"> Ingresa código de verificación <span class="pull-right btn enviar-de-nuevo fa fa-refresh"></span> </p>
    <p class="texto-step message-number-send"> Hemos enviado el codigo de verificación a su telefono <span class="number-register">{{ $number }}</span> </p>

    <div class="form-group has-feedback">
      <input 
      type="text"
      maxlength="4"
      placeholder="Ingrese los 4 digitos del codigo"
      class="form-control" 
      name="code_seguridad">
    <span class="fa fa-lock form-control-feedback"></span>

    <p class="texto-step message-number-send"> Si no le ha llegado el código de verificacion por favor presione 
    <a href="{{ route('usuario.reenviar_codigo') }}"> AQUÍ </a>
    </p>


    </div>

    <div class="row validation-btn">
      <div class="col-xs-12 mb-2" style="margin-bottom:5px">
        <a href="#" data-url="{{ route('usuario.verificar_codigo') }}" class="btn send-code-seguridad btn-primary btn-flat">Validar Codigo </a>
        @include('partials.button_logout', ['clases' => 'pull-right' ])
      </div>
    </div>



    
  </div>


</form>