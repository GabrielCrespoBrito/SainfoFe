<div class="row">  

  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon"> Contraseña del certificado </span>
      <input class="form-control input-sm" required name="cert_password" type="password" value="">  
      <span class="input-group-addon btn bg-gray show-password"><i class="fa fa-eye"></i></span>
    </div>
    <span class="help-block">{{ $errors->first('cert_key') }}</span>
  </div>

  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon"> Usuario sol </span>
      <input class="form-control input-sm" required name="usuario_sol" type="password" value="">  
      <span class="input-group-addon btn bg-gray show-password"><i class="fa fa-eye"></i></span>
    </div>
    <span class="help-block">{{ $errors->first('cert_key') }}</span>
  </div>

    <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon"> Clave sol </span>
      <input class="form-control input-sm" required name="clave_sol" type="password" value="">  
      <span class="input-group-addon btn bg-gray show-password"><i class="fa fa-eye"></i></span>
    </div>
      <span class="help-block">{{ $errors->first('cert_key') }}</span>
  </div>


</div>

