@php
  $direccion = $direccion ?? '';
  $nombre_comercial = $nombre_comercial ?? '';
  $email = $email ?? '';
@endphp 

<form method="POST" id="form-empresa" autocomplete="off" action="{{ route('usuario.store_empresa_informacion') }}">

  <div class="step ruc number-phone">
    <div class="input-group input-group-sm">
      <input type="text" name="ruc" class="form-control">
        <span class="input-group-btn">
          <button type="button" data-route="{{ route('consulta_ruc') }}" class="ruc-consulta btn btn-info btn-flat"> <span class="fa fa-search"> </span> </button>
        </span>
    </div>
  </div>

  <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="text" name="razon_social" class="form-control" readonly placeholder="Razón Social" value="" >
      <span class="fa fa-bookmark-o form-control-feedback"></span>
    </div>
  </div>

  <div class="step number-phone">
    <div class="form-group has-feedback">
    <input type="text" name="nombre_comercial" class="form-control" required placeholder="Nombre Comercial" value="">
      <span class="fa fa-bookmark-o form-control-feedback"></span>
    </div>
  </div>

 <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="text" name="direccion" class="form-control" required value="" placeholder="Dirección">
      <span class="fa fa-map form-control-feedback"></span>
    </div>
  </div>

 <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="email" name="email" class="form-control" required value="" autocomplete="off" placeholder="Correo electronico">
      <span class="fa fa-envelope form-control-feedback"></span>
    </div>
  </div>


  <div class="row validation-btn">
    <div class="col-xs-12 mb-2" style="margin-bottom:5px">
      <button type="submit" class="btn enviar btn-primary btn-flat "> 
      
      <span class="estado-cargando" style="display:none"><span class="fa fa-spinner fa-spin"></span>  Cargando... </span> 
      <span class="estado-default"> Guardar</span> 
      
      </button>
      @include('partials.button_logout', ['clases' => 'pull-right' ])
    </div>
  </div>


</form>