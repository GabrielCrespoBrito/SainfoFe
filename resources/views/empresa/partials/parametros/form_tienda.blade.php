@php
  $area_admin = $area_admin ?? false;
  $route =  route('admin.empresa.update_credenciales_tienda', $empresa->empcodi);
  $woocomerce_api_url = $empresa->getDataAditional('woocomerce_api_url');
  $woocomerce_client = $empresa->getDataAditional('woocomerce_client');
  $woocomerce_client_key = $empresa->getDataAditional('woocomerce_client_key');
@endphp


<div class="row">
  <div class="col-md-12">
    <form id="form-cert" action="{{ $route }}" method="post">
      @csrf
      
      <div class="row">
        <div class="col-md-12">
          <div class="title"> Información </div>
{{--  --}}
<div class="row">  

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon"> Tienda Url </span>
      <input class="form-control input-sm" required name="woocomerce_api_url" type="url" value="{{ old('woocomerce_api_url', $woocomerce_api_url) }}" placeholder="https://tiendaonline.com" >  
    </div>
    <span class="help-block">{{ $errors->first('woocomerce_api_url') }}</span>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon"> Cliente Llave </span>
      <input class="form-control input-sm" required name="woocomerce_client" type="password" value="{{ old('woocomerce_client', $woocomerce_client) }}" placeholder="ck_89941cbcd53214b4f7c1a9a9a3fd4d6d00000000">  
      <span class="input-group-addon btn bg-gray show-password"><i class="fa fa-eye"></i></span>
    </div>
    <span class="help-block">{{ $errors->first('woocomerce_client') }}</span>
  </div>

    <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon"> Cliente Clave Secreta </span>
      <input class="form-control input-sm" required name="woocomerce_client_key" type="password" value="{{ old('woocomerce_client_key', $woocomerce_client_key) }}" placeholder="cs_392dc7134731b65fc6644d61523970a300000000">  
      <span class="input-group-addon btn bg-gray show-password"><i class="fa fa-eye"></i></span>
    </div>
      <span class="help-block">{{ $errors->first('woocomerce_client_key') }}</span>
  </div>
</div>

</div>
  {{--  --}}

        <div class="col-md-12">
          <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"> </span> Guardar </button>
        </div>
      </div>
    </form>
  </div>

</div>



