<div class="row row_data_referencia">  

  <div class="div_datos_referenciales block {{ $hide ? 'hide' : '' }}">
    
  <p class="titulo_seccion"> Datos referenciales </p>


  <div class="form-group col-md-2 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Doc Ref</span>
      <input class="form-control input-sm group_ref" value="{{ $create ? '' ? $venta->VtaTDR : '' : '' }}"  name="ref_documento" disabled="disabled" placeholder="">      
    </div>
  </div>

  <div class="form-group col-md-2 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">Serie</span>   
      <input class="form-control input-sm group_ref" value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaSeriR : '' : '' }}" name="ref_serie" disabled="disabled">      
    </div>
  </div>

  <div class="form-group col-md-2 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">Número</span>   
      <input class="form-control input-sm group_ref" value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaNumeR : '' : '' }}" name="ref_numero" disabled="disabled">      
    </div>
  </div>

  <div class="form-group col-md-2 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">Fecha</span>   
      <input class="form-control input-sm group_ref" readonly="readonly" value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaFVtaR : '' : '' }}" name="ref_fecha">      
    </div>
  </div>

  <div class="form-group col-md-4 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">Tipo</span>   
      @if($create)
      <select class="form-control input-sm group_ref" name="ref_tipo">      
        @foreach( $tipos_notacredito as $tipo_notacredito )
          <option {{ $loop->first ? 'selected=selected' : '' }} value="{{ $tipo_notacredito->id }}"> ({{ $tipo_notacredito->id }})  {{ $tipo_notacredito->descripcion }}  </option>
        @endforeach        
      </select>

      @else
      <input class="form-control input-sm group_ref" value="{{ $venta->vtaadoc }}" name="ref_motivo" disabled="disabled">      
      @endif
    </div>
  </div>

  </div>

</div>