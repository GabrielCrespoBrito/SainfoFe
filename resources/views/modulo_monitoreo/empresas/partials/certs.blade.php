<div> 
  <h5> </span> Certificados </h5>

  <div class="row">  
    
    <div class="form-group {{ $errors->has('cert_key') ? 'has-error' : '' }} col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">.key </span>
        <input accept=".key" {{ $isCreate ? '' : '' }} class="form-control input-sm" name="cert_key" type="file" value="">  
      </div>
      <span class="help-block">{{ $errors->first('cert_key') }}</span>
    </div>

    <div class="form-group {{ $errors->has('cert_cer') ? 'has-error' : '' }} col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">.cer</span>
        <input accept=".cer" {{ $isCreate ? '' : '' }} class="form-control input-sm" name="cert_cer" type="file" value="">
      </div>
      <span class="help-block">{{ $errors->first('cert_cer') }}</span>          
    </div>
        
    <div class="form-group {{ $errors->has('cert_pfx') ? 'has-error' : '' }} col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">.pfx</span>
        <input accept=".pfx" {{ $isCreate ? '' : '' }} class="form-control input-sm" name="cert_pfx" type="file" value="">     
      </div>
      <span class="help-block">{{ $errors->first('cert_pfx') }}</span>
    </div>          

  </div>

</div>
