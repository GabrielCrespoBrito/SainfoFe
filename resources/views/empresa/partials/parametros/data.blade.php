@include('partials.errors_html')

@php
  $notFieldShow = [];
@endphp

    @php
      $formato = $empresa->fe_formato;
      $proforma_igv = $empresa->getDataAditional('proforma_igv');
      $configuracion_igv = $empresa->getDataAditional('configuracion_igv');
    @endphp    
    <div class="form-group col-md-3 {{ $errors->has('fe_formato') ? 'has-error' : '' }}">
      <label for="_fe_formato" class="oneline"> Formato de Impresión por Defecto </label>
        <select id="_fe_formato"  class="form-control input-sm" name="fe_formato">
            <option {{  $formato == "0" ?  'selected=selected' : ''  }} value="0"> A4  </option>
            <option {{  $formato == "1" ?  'selected=selected' : ''  }} value="1"> A5  </option>
            <option {{  $formato == "2" ?  'selected=selected' : ''  }} value="2"> Ticket  </option>
        </select>
    </div>

    <div class="form-group col-md-3 {{ $errors->has('fe_formato') ? 'has-error' : '' }}">
      <label for="proforma_igv" class="oneline"> Mostrar IGV En Impr. de Cotización/Orden de Pago/Pre Venta </label>
        <select id="proforma_igv" class="form-control input-sm" name="proforma_igv">
            <option {{  $proforma_igv == "0" ?  'selected=selected' : ''  }} value="0"> No  </option>
            <option {{  $proforma_igv == "1" ?  'selected=selected' : ''  }} value="1"> Si  </option>
        </select>
    </div>

    <div class="form-group col-md-3">
      <label for="proforma_igv" class="oneline"> Configuración de IGV  </label>
        <select id="proforma_igv" class="form-control input-sm" name="configuracion_igv">
          @foreach( $configuraciones_igv as $c_igv )
            <option {{ $c_igv->codigo == $configuracion_igv ? 'selected=selected' : '' }} value="{{ $c_igv->codigo }}"> {{ $c_igv->descripcion }} </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
      <label for="proforma_igv" class="oneline"> Tipo de Caja  </label>
        <select id="proforma_igv" class="form-control input-sm" name="tipo_caja">
            <option value="0" {{ $empresa->getTipoCaja() == "0" ? 'selected' : '' }}> Por Usuario </option>
            <option value="1" {{ $empresa->getTipoCaja() == "1" ? 'selected' : '' }}> Por Local </option>
        </select>
    </div>

  @foreach( $parametros as $name => $value )
    @php
      $info_settings = $settings->getInfoSetting( $name );
      //dump( "$name - $value");
    @endphp


    @if( $info_settings == null )
      @continue
    @endif

    @if( !in_array($name, $notFieldShow)  )
    <div class="form-group col-md-3 {{ $errors->has($name) ? 'has-error' : '' }}">
      <label for="_{{ $name }}" class="oneline" title="{{ $info_settings['name'] }}"> {{ $info_settings['name'] }} {{ $info_settings['required'] ? '(*)' : '' }} </label>
      @if( $info_settings['type'] == 'input' )      
        <input id="_{{ $name }}" class="form-control input-sm" type="text" value="{{ $value }}" name="{{ $name }}">
      @elseif( $info_settings['type'] == 'select' )
        <select id="_{{ $name }}" {{ $info_settings['required'] ? 'required' : '' }} class="form-control input-sm" name="{{ $name }}">
          @foreach( $info_settings['options'] as $key => $text )
            <option {{ $key == $value ? 'selected=selected' : '' }} value="{{ $key }}"> {{ $text  }} </option>
          @endforeach
        </select>
        @if($errors->has($name))
          <span class="help-block"> {{ $errors->first($name) }}</span>
        @endif
      @endif
    </div>
    @endif
  @endforeach
  
{{-- </div> --}}