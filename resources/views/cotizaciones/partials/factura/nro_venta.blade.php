<div class="row">
  
  <div class="form-group col-md-3 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Nro Oper</span>

      @php
      if( $create ){
        // $id = $is_preventa ? $id_nuevo_preventa : $id_nuevo;
        $id = $id;
      }
      else {
        $id = $cotizacion->CotNume;
      }
      @endphp
        <input class="form-control input-sm" name="codigo_venta" type="text" readonly="readonly" value="{{
        $id }}">
    </div>
  </div>

  {{-- @dd( $create , $cotizacion->TidCodi); --}}
  <div class="form-group col-md-3 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Tipo documento</span>
        <select name="tipo_documento" class="form-control input-sm">
          @php
            $tidcodi_selected = $create ? '01' : $cotizacion->TidCodi;
          @endphp
          <option {{ $tidcodi_selected == "01" ? 'selected=selected' : '' }} value="01"> FACTURA </option>
          <option {{ $tidcodi_selected == "03" ? 'selected=selected' : '' }} value="03"> BOLETA </option>
          <option {{ $tidcodi_selected == "52" ? 'selected=selected' : '' }} value="52"> TICKET VENTA </option>
        </select>   
    </div>
  </div>

  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">F. Emis</span>
        <input name="fecha_emision" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ $date }}" required="required"  class="form-control input-sm datepicker" value="{{ $create ? $date : $cotizacion->CotFVta  }}" type="text">
    </div>
  </div>

  <div class="form-group col-md-3 no_pl">  
    <div class="input-group">
      <span class="input-group-addon">F. Venc</span>
        <input name="fecha_vencimiento" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ $date }}" required="required"  class="form-control input-sm datepicker" value="{{  $create ? $date : $cotizacion->CotFVen   }}" type="text">            
    </div>
  </div>

</div>
