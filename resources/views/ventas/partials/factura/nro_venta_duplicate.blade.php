<div class="row">  

  <div class="form-group col-md-3 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Nro venta</span>
        <input class="form-control input-sm" name="codigo_venta" type="text" readonly="readonly" value="{{ $create ? $id_nuevo : $venta->VtaOper }}">     
    </div>
  </div>

  <div class="form-group col-md-4 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Tipo documento</span>
      @if($create)
        <select name="tipo_documento" class="form-control input-sm">
          @foreach( $tipos_documentos as $tipo_documento )                    
            <option {{ $create ? '' : 'selected=selected'  }} data-series="{{ json_encode($tipo_documento['series']) }}" value="{{ $tipo_documento['id'] }}"> {{ $tipo_documento['nombre'] }} </option>
          @endforeach
        </select>      
      @else
        <input name="tipo_documento" class="form-control input-sm" value="{{ $venta->VtaSeri }}">
      @endif
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Nro documento</span>
        @if($create)
          <select name="serie_documento" class="form-control input-sm">        
            @foreach( $tipos_documentos->first()['series'] as $serie )
              <option data-codigo="{{ $serie['nuevo_codigo'] }}" value="{{ $serie['id'] }}"> {{ $serie['nombre'] }} </option>
            @endforeach
          </select>

        @else                         

        <input name="serie_documento" class="form-control input-sm" value="{{ $venta->VtaNumee }}">        
          <!-- else here -->
        @endif
    </div>
  </div>

  <div class="form-group col-md-1 no_pl">  
    <div class="input-group">
      <input name="nro_documento" readonly="readonly" required="required" class="form-control input-sm" value="{{ $create ? $tipos_documentos->first()['series'][0]['nuevo_codigo'] : $venta->VtaNumee }}" type="text">        
    </div>
  </div>

</div>
