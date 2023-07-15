<div class="row">
  {{-- @include('partials.errors_html') --}}
</div>

<div class="row">  

  <div class="form-group {{ $errors->has('cert_key') ? 'has-error' : '' }} col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">.key </span>
        <input class="form-control input-sm"  accept=".key" name="cert_key" required type="file" value="">  
    </div>
    <span class="help-block">{{ $errors->first('cert_key') }}</span>
  </div>

  <div class="form-group {{ $errors->has('cert_cer') ? 'has-error' : '' }} col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">.cer</span>
        <input class="form-control input-sm" accept=".cer" name="cert_cer" type="file"  required value="">     
    </div>
    <span class="help-block">{{ $errors->first('cert_cer') }}</span>          
  </div>

  <div class="form-group {{ $errors->has('cert_pfx') ? 'has-error' : '' }} col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">.pfx</span>
        <input class="form-control input-sm" accept=".pfx" name="cert_pfx" type="file" required value="">     
    </div>
    <span class="help-block">{{ $errors->first('cert_pfx') }}</span>
  </div>

</div>

