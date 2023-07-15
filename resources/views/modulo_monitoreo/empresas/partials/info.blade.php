  <div class="d">

  <h5>  Información </h5>

  <div class="row">

    <div class="form-group {{ $errors->has('razon_social') ? 'has-error' : '' }}  col-md-2">
      <label> Cod Interno*</label>
      <input name="code" value="{{ old('razon_social' , $empr->code ) }}"   required="required" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('code') }}</span>

    </div>

    <div class="form-group {{ $errors->has('razon_social') ? 'has-error' : '' }}  col-md-5">
      <label> Razón social *</label>
      <input placeholder="SAINFO EPR" name="razon_social" value="{{ old('razon_social' , $empr->razon_social ) }}"   required="required" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('razon_social') }}</span>

    </div>

    <div class="form-group {{ $errors->has('ruc') ? 'has-error' : '' }}  col-md-5">
      <label> RUC * </label>
      <input placeholder="20458965236" name="ruc" type="text" minlength="11" maxlength="11" value="{{ old('ruc' , $empr->ruc ) }}" required="required" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('ruc') }}</span>
    </div>
            
  </div>




  
  <div class="row">

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}  col-md-6">
      <label> Email </label>
      <input placeholder="email@gmail.com" name="email" type="email" value="{{ old('email' , $empr->email ) }}" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('email') }}</span>
    </div>

    <div class="form-group {{ $errors->has('telefono') ? 'has-error' : '' }}  col-md-6">
      <label> Telefono </label>
      <input placeholder="588744552" name="telefono" value="{{ old('telefono' , $empr->telefono ) }}" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('telefono') }}</span>
    </div>
            
  </div>

  <div class="row">

    <div class="form-group col-md-4">
      <label> Usuario Sol </label>
      <input name="usuario_sol" value="{{ old('usuario_sol' , $empr->usuario_sol ) }}"   required="required" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('code') }}</span>

    </div>

    <div class="form-group col-md-4">
      <label> Clave Sol *</label>
      <input name="clave_sol" type="text" value="{{ old('razon_social' , $empr->clave_sol ) }}" required="required" class="form-control input-sm">
      <span class="help-block">{{ $errors->first('razon_social') }}</span>

    </div>

    <div class="form-group col-md-4">
      <label> Proveedor  </label>

      <select name="proveedor" type="password" value="{{ old('razon_social' , $empr->clave_sol ) }}" required="required" class="form-control input-sm">
        <option value="sunat">Sunat </option>
        <option value="sunat">NubeFact  </option>
      </select>

    </div>

           
  </div>



  <div class="row">
    
    <div class="form-group {{ $errors->has('descripcion') ? 'has-error' : '' }}  col-md-12">
      <label> Nota </label>
    <textarea placeholder="Comentario " name="descripcion" class="form-control input-sm"> {{ old('descripcion' , $empr->descripcion ) }} </textarea>
      <span class="help-block">{{ $errors->first('descripcion') }}</span>
    </div>

  </div>  

  </div>