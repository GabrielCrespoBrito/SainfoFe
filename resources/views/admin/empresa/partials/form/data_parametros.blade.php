<div class="row">  
  <div class="col-md-12">   
    <input type="checkbox" name="facturacion" value="1"> Facturación electronica
  </div>
</div>

<div class="parametros_adicionales">

<div class="row">  

  <div class="form-group {{ $errors->first('clave_firma') ? 'has-error' : '' }} col-md-4">  
    <div class="input-group">
      <span class="input-group-addon c-input">Clave Firma</span>
        <input class="form-control input-sm" required name="clave_firma" type="password" value="{{ old('clave_firma') }}">     
    </div>
  </div>
  
  <div class="form-group {{ $errors->first('formato_hoja') ? 'has-error' : '' }} col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Formato Hoja</span>
        <select class="form-control input-sm" required name="formato_hoja" type="text" value="">     
          <option value="0">A4-1</option>
          <option value="1">A5</option>
          <option value="2">Ticket</option>
        </select>
    </div>
  </div>
  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Versión XML</span>
        <select style="padding:0" required class="form-control input-sm" name="version_xml" type="text" value="">     
          <option value="2.0" selected>2.0</option>
          <option value="2.1">2.1</option>          
        </select>
    </div>   
    </div>
  </div>
</div>

<div class="row">  
  <div class="form-group {{ $errors->first('usuario_sol') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Usuario SOL</span>
        <input class="form-control input-sm" required name="usuario_sol" type="text" value="{{ old('usuario_sol') }}">     
    </div>
  </div>
  <div class="form-group {{ $errors->first('clave_sol') ? 'has-error' : '' }} col-md-6">  
    <div class="input-group">
      <span class="input-group-addon c-input">Clave SOL</span>
        <input class="form-control input-sm" required name="clave_sol" type="password" value="{{ old('clave_sol' ) }}">     
    </div>
  </div>
</div>

<div class="form-group col-md-12 no_pl">  
  <div class="input-group">
    <span class="input-group-addon">Url del servicio</span>
      <select class="form-control input-sm" name="tipo_envio_servicio" type="text">     
        <option value="0">DESARROLLO</option>
        <option value="1"">HOMOLOGACIÓN</option>
        <option value="2"">PRODUCCIÓN</option>         
      </select>
  </div>
</div>

<div class="row">  
  <div class="col-md-4">  
    <span class="form-group span-doc_envios" >Enviar Docs a la Sunat</span>
  </div>
  <div class="form-group col-md-2">  
      <div class="checkbox" >
        <label> <input type="checkbox" name="documento_enviar[]" value="factura"> Factura</label>
    </div>
  </div>
  <div class="form-group col-md-2">  
      <div class="checkbox">
        <label> <input type="checkbox" name="documento_enviar[]" value="nota_credito"> Nota crédito</label>
    </div>
  </div>
  <div class="form-group col-md-2">  
      <div class="checkbox">
        <label> <input type="checkbox" name="documento_enviar[]" value="nota_debito"> Nota débito</label>
    </div>
  </div>
  <div class="form-group col-md-2">  
    <div class="checkbox">
      <label> <input type="checkbox" name="documento_enviar[]" value="boleta"> Boleta</label>
    </div>
  </div>
</div>





  <div class="form">
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
  <div class="form-group col-md-6">  
      <div class="checkbox">
        <label> <input type="checkbox" name="precio_igv" value="1">Precio venta incluye IGV</label>
    </div>
  </div>
  <div class="form-group col-md-6">  
      <div class="checkbox">
        <label> <input type="checkbox" name="imprimir" value="1"> Imprimir directo</label>
    </div>
  </div>
</div>

</div>