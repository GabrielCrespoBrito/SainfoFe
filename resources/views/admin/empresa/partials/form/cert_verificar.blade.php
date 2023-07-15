  @php
    $data = session('checkCertificado');    
    $cer = '';
    $key = '';
    $pfx = '';
    $find = "Se encontro este certificado";
    $notFind = "No se encontro";
    if($data){      
      $cer = $data["key"]['exists'] ? $find : $notFind;
      $key = $data["cer"]['exists'] ? $find : $notFind; 
      $pfx = $data["pfx"]['exists'] ? $find : $notFind;
    }
  @endphp

  <form action="{{route('empresa.checkCertificados', $empresa->empcodi  ) }}" method="post">
    @csrf
    <div class="form-group col-md-12 {{ $data ? return_str( $data['cer'] , true , 'has-success' , 'has-error' ) : '' }}">  
      <div class="input-group">
        <span class="input-group-addon">.key </span>
          <input class="form-control input-sm" accept=".key" readonly="readonly" type="text" value="{{ $cer }}">
      </div>
    </div>


    <div class="form-group  col-md-12 {{ $data ? return_str( $data['key'] , true , 'has-success' , 'has-error' ) : '' }}">  
      <div class="input-group">
        <span class="input-group-addon">.cer</span>
          <input class="form-control input-sm" accept=".cer" readonly="readonly" name="cert_cer" type="text" value="{{ $cer }}">     
      </div>
    </div>

    <div class="form-group col-md-12 {{ $data ? return_str( $data['pfx'] , true , 'has-success' , 'has-error' ) : '' }}">  
      <div class="input-group">
        <span class="input-group-addon">.pfx</span>
          <input class="form-control input-sm" accept=".pfx" readonly="readonly" name="cert_pfx" type="text" value="{{ $pfx }}">     
      </div>
    </div>

    <div class="col-md-12">
      <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-save"></span> Verificar certificados subidos </button>
    </div>

  </form>