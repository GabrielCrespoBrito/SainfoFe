@php
  $select_tipo = $select_tipo ?? null; 
  $empresa = $empresa ?? null; 
@endphp

 <div class="row">

  @if($select_tipo)
  <div class="form-group  col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">T.Ambiente</span>
        <select name="tipo" required class="form-control">
          <option  {{ optional($empresa)->tipo == "web" ? 'selected' : '' }} value="web"> Web </option>
          <option {{ optional($empresa)->tipo == "escritorio" ? 'selected' : '' }} value="escritorio"> Escritorio </option>
        </select>
    </div>
  </div>
  @endif

  <div class="form-group {{ $select_tipo ? 'col-md-4' : 'col-md-6' }}">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Certificado</span>
      <input value="{{ optional($empresa)->venc_certificado }}" class="form-control input-sm" required name="venc_certificado" type="date" value="">
    </div>
  </div>

  <div class="form-group {{ $select_tipo ? 'col-md-4' : 'col-md-6' }}">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Suscripción</span>
      <input value="{{ optional($empresa)->getFechaSuscripcion() }}" class="form-control input-sm" required name="fecha_suscripcion" type="date" value="">
    </div>
  </div>


  </div>