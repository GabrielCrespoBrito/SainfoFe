<div class="row">  

  <div class="form-group col-md-4 hidden-sm hidden-xs">
    <div class="input-group">
      <span class="input-group-addon">Nro venta</span>
        <input 
          class="form-control input-sm"
          name="codigo_venta"
          type="text"
          readonly="readonly"
          value="{{ $create ? $id_nuevo : $venta->VtaOper }}">     
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Tipo documento</span>
      @if($create)
        <select name="tipo_documento" class="form-control input-sm">
          @php
            $has_selected = false;
            $tipo_documento_selected = null;
          @endphp

          @foreach( $tipos_documentos as $tipo_documento )

            @php
              if( $has_selected == false ){
                if( $tipo_documento['id'] == $tipo_documento_defecto ){
                  $tipo_documento_selected = $tipo_documento;
                  $has_selected = true;
                }
                elseif( $loop->last ){
                  $tipo_documento_selected = $tipos_documentos->first();
                  $has_selected = true;
                }
              }
            @endphp
            
          <option {{ $tipo_documento['id'] == $tipo_documento_defecto ? 'selected=selected' : ''  }} 
          data-series="{{ json_encode($tipo_documento['series']) }}" 
          value="{{ $tipo_documento['id'] }}"> {{ $tipo_documento['nombre'] }} </option>
          
          @endforeach
        </select>      
      @else
        <input readonly="readonly" name="tipo_documento" class="form-control input-sm" value="{{ $venta->getNombreTipoDocumento() }}">
      @endif
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Nro documento</span>
        @if( $create )
          <select {{ (int) $tipo_documento_selected }} name="serie_documento" class="form-control input-sm">
            @if( $tipo_documento_selected )
              @foreach( $tipo_documento_selected['series'] as $serie )
                <option {{ $serie['defecto'] ? 'selected=selected' : ''  }} data-codigo="{{ $serie['nuevo_codigo'] }}" value="{{ $serie['id'] }}"> {{ $serie['nombre'] }} </option>
              @endforeach
            @endif
          </select>
        @else                         
          <input name="serie_documento" readonly="readonly" class="form-control input-sm" value="{{ $venta->VtaSeri }}">
        @endif
      </select>

      <span class="input-group-addon nro_documento"> {{ $create ? $tipo_documento_selected['nuevo_codigo_defecto'] ?? '' : $venta->VtaNumee }} </span>
    </div>

  </div>
</div>