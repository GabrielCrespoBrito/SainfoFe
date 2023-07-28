

<div class="row">  
  <div class="form-group col-md-2">  
    <div class="input-group">
      <span class="input-group-addon">ID</span>
        <input class="form-control input-sm" type="text" required value="" disabled="disabled">     
    </div>
  </div>

  <div class="form-group col-md-10 {{ $errors->has('nombre_empresa') ? 'has-error' : '' }}">  
    <div class="input-group">
      <span class="input-group-addon">Razón social</span>
        <input class="form-control input-sm" type="text"  name="nombre_empresa" required value="{{ old('nombre_empresa') }}"> 
    </div>
  </div>
</div>

<div class="row">  
  <div class="form-group {{ $errors->has('ruc') ? 'has-error' : '' }} col-md-5">  
    <div class="input-group">
      <span class="input-group-addon">RUC</span>
        <input class="form-control input-sm" name="ruc" type="text" required value="{{ old('ruc') }}">
      <span class="input-group-addon">
        <a href="#" data-url="{{ route('consulta_ruc') }}" class="search-ruc btn btn-xs btn-flat btn-default"> <span class="fa fa-search"></span> </a>
      </span>
    </div>
  </div>
  <div class="form-group {{ $errors->has('nombre_comercial') ? 'has-error' : '' }} col-md-7">  
    <div class="input-group">
      <span class="input-group-addon">Nombre Comercial</span>
      <input class="form-control input-sm"  name="nombre_comercial" type="text" required value="{{ old('nombre_comercial') }}">     
    </div>
  </div>
</div>

<div class="row">  
  <div class="form-group {{ $errors->has('direccion') ? 'has-error' : '' }} col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Dirección</span>
        <input class="form-control input-sm" placeholder="Av. San Juan, Calle 5276, #5252"  name="direccion" type="text" required value="{{ old('direccion') }}">
    </div>
  </div>
</div>

<div class="row">  
  <div class="form-group {{ $errors->has('ubigeo') ? 'has-error' : '' }} col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Ubigeo</span>
        <input class="form-control input-sm"  name="ubigeo" type="text" required value="{{ old('ubigeo') }}">     
    </div>
  </div>
  <div class="form-group {{ $errors->has('departamento') ? 'has-error' : '' }} col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Depar</span>
        <input class="form-control input-sm"  name="departamento" type="text" required value="{{ old('departamento') }}">     
    </div>
  </div>
  <div class="form-group {{ $errors->has('provincia') ? 'has-error' : '' }} col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Prov</span>
        <input class="form-control input-sm"  name="provincia" type="text" required value="{{ old('provincia') }}">     
    </div>
  </div>
  <div class="form-group {{ $errors->has('distrito') ? 'has-error' : '' }} col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Dist</span>
        <input class="form-control input-sm"  name="distrito" type="text" required value="{{ old('distrito') }}">     
    </div>
  </div>      
</div>

<div class="row">  
  <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Email</span>
        <input class="form-control input-sm"  name="email" type="text" value="{{ old('email') }}">     
    </div>
  </div>
  <div class="form-group {{ $errors->has('telefonos') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Telefonos</span>
        <input class="form-control input-sm"  name="telefonos" type="text" value="{{ old('telefonos') }}">     
    </div>
  </div>
</div>

<div class="row">  
  <div class="form-group {{ $errors->has('rubro') ? 'has-error' : '' }} col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Rubro</span>
        <input class="form-control input-sm"  name="rubro" type="text" value="{{ old('rubro') }}">     
    </div>
  </div>
</div>

{{-- <div class="row">  


  <div class="form-group {{ $errors->has('logo_principal') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Logo </span>
        <input class="form-control input-sm" name="logo_principal" type="file" required value="">     
    </div>

  </div>


  <div class="form-group {{ $errors->has('logo_secundario') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Logo 2</span>
        <input class="form-control input-sm" name="logo_secundario" type="file" value="">     
    </div>

  </div> --}}

</div>


