@php
  $empresa = $empresa ?? null; 
@endphp

 <div class="row empresa-parametros">

  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Emis. Cert</span>
      <input value="{{ optional($empresa)->emis_certificado }}" class="form-control input-sm" required name="emis_certificado" type="date" value="">
    </div>
  </div>

  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Venc. Cert</span>
      <input value="{{ optional($empresa)->venc_certificado }}" class="form-control input-sm" required name="venc_certificado" type="date" value="">
    </div>
  </div>

  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Suscripción</span>
      <input disabled value="{{ optional($empresa)->getFechaSuscripcion() }}" class="form-control input-sm" required name="fecha_suscripcion" type="date" value="">
    </div>
  </div>

  @php
  $usuarios = App\User::all();
  @endphp

  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon"> Usuario </span>
      @if( $empresa == null )
      
      <select class="form-control input-sm" required name="usuario">
      @foreach( $usuarios as $usuario )
      <option value="{{ $usuario->id() }}"> {{ $usuario->completeName() }} 
      </option>
      @endforeach
      </select>

      @else

      <input disabled class="form-control input-sm" name="fecha_suscripcion" value="{{ optional($empresa->userOwner())->completeName() }}">

      @endif

    </div>
  </div>

  </div>