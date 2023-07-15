<div class="row">  
  <div class="col-md-12">   
    <input type="checkbox" name="facturacion" value="1" {{ $empresa->OPC ? 'checked=checked' : '' }}> Facturación electronica
  </div>
</div>

<div class="parametros_adicionales">

<div class="block" style="display:{{ $empresa->OPC ? 'none' : 'block' }}"></div>

<div class="row">  

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon c-input">Certificado Nomb</span>
        <input class="form-control input-sm" name="cert_nomb" type="text" value="{{ old('cert_nomb', $empresa->FE_CERT  ) }}">     
    </div>
  </div>

  <div class="form-group col-md-2 no_p">  
    <div class="input-group">
      <span class="input-group-addon c-input">Clave Firma</span>
        <input class="form-control input-sm" name="clave_firma" type="password" value="{{ old('clave_firma', $empresa->FE_CLAVE  ) }}">     
    </div>
  </div>


  <div class="form-group col-md-2 no_p">  
    <div class="input-group">
      <span class="input-group-addon no_p">Formato Hoja</span>
        <select class="form-control input-sm" name="formato_hoja" type="text" value="">     
          <option {{ $empresa->isA4() ? 'selected=selected' : ''  }} value="0">A4</option>
          <option {{ $empresa->isA5() ? 'selected=selected' : ''  }} value="1">A5</option>
          <option {{ $empresa->isTicket() ? 'selected=selected' : '' }} value="2">Ticket</option>  
        </select>
    </div>
  </div>

  <div class="form-group col-md-2 no_p">  
    <div class="input-group">
      <span class="input-group-addon no_p">Formato A4</span>
        <select class="form-control input-sm" name="formato_a4" type="text" value="">     
          <option {{ $empresa->FE_DATA == 1 ? 'selected=selected' : ''  }} value="1"> Normal  </option>
          <option {{ $empresa->FE_DATA == 2 ? 'selected=selected' : ''  }} value="2"> Imagen adicional </option>
          <option {{ $empresa->FE_DATA == 3 ? 'selected=selected' : '' }} value="3"> 3 Columnas </option>  
        </select>
    </div>
  </div>

  <div class="form-group col-md-2 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">XML</span>
        <select style="padding:0" class="form-control input-sm" name="version_xml" type="text" value="">     
          <?php $is_xml_2_1 = $empresa->isXml_2_1(); ?>
          <option value="2.0" {{ $is_xml_2_1 ? "" : "selected" }}>2.0</option>
          <option value="2.1" {{ $is_xml_2_1 ? "selected" : "" }}>2.1</option>          
        </select>
    </div>       
  </div>

</div>

<div class="row">  

  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Usuario SOL</span>
        <input class="form-control input-sm" name="usuario_sol" type="text" value="{{ old('usuario_sol', $empresa->FE_USUNAT  ) }}">     
    </div>
  </div>
  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon c-input">Clave SOL</span>
        <input class="form-control input-sm" name="clave_sol" type="password" value="{{ old('clave_sol', $empresa->FE_UCLAVE  ) }}">     
    </div>
  </div>

</div>

<div class="row">  

  <div class="col-md-4">  
    <span class="form-group span-doc_envios" >Enviar Docs a la Sunat</span>
  </div> 

  <div class="form-group col-md-2">  
      <div class="checkbox" >
        <label> <input type="checkbox" name="fe_envfact" {{ $empresa->envioFactura() ? ' checked=checked' : '' }} value="factura"> Factura</label>
    </div>
  </div>

  <div class="form-group col-md-2">
      <div class="checkbox">
        <label> <input type="checkbox" name="fe_envncre" {{ $empresa->envioNotaCredito() ? ' checked=checked' : '' }} value="nota_credito"> Nota crédito</label>
    </div>
  </div>

  <div class="form-group col-md-2">  
      <div class="checkbox">
        <label> <input type="checkbox" name="fe_envndebi" {{ $empresa->envioNotaDebito() ? ' checked=checked' : '' }} value="nota_debito"> Nota débito</label>
    </div>
  </div>

  <div class="form-group col-md-2">
    <div class="checkbox">
      <label> <input type="checkbox" name="fe_envbole" {{ $empresa->envioBoleta() ? ' checked=checked' : '' }} value="boleta"> Boleta</label>
    </div>
  </div>

</div>

<div class="row">

  <div class="form-group col-md-8">    
    <div class="input-group">
      <span class="input-group-addon">Servicio </span>
        <select class="form-control input-sm" name="fe_servicio" type="text">     
          <option value="1" {{ $empresa->isProveedor("1") ? 'selected=selected' : ''  }}> SUNAT </option>
          <option value="2" {{ $empresa->isProveedor("2") ? 'selected=selected' : ''  }}> OSE  </option>
        </select>
    </div>
  </div>

  <div class="form-group col-md-4">    
    <div class="input-group">
      <span class="input-group-addon">Ambiente </span>
        <select class="form-control input-sm" name="fe_ambiente" type="text">     
          <option value="1" {{ $empresa->isAmbiente("1") ? 'selected=selected' : '' }}>PRODUCCIÓN</option>
          <option value="2" {{ $empresa->isAmbiente("2") ? 'selected=selected' : '' }}>DESARROLLO</option>
        </select>
    </div>
  </div>

</div>

  <div class="row">  
    <div class="form-group col-md-12">  
      <div class="input-group">
        <span class="input-group-addon">Url Consulta Doc</span>
          <input class="form-control input-sm" name="url_consulta" type="text" value="{{ old('url_consulta', $empresa->fe_consulta  ) }}">     
      </div>
    </div>
  </div>

  <div class="row">  
      <div class="form-group col-md-6">  
        <div class="checkbox">
          <label> 
          <input type="checkbox" name="precio_igv" value="1" {{ $empresa->PrecIIGV == "1" ? 'checked=checked' : ''}}> 
          Precio venta incluye IGV</label>
      </div>
    </div>

  </div>
</div>