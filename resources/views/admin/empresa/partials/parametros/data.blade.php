@include('partials.errors_html')

@if( !$has_option  )
  <div class="title">
      <span> No se ha guardado la información de la empresa </span>
  </div>
@endif

<div class="row">  

  <div class="especial_field">

    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon"> Servidor </span>
          <input class="form-control input-sm" type="text" value="{{ $parametros['EmaServ'] }}" name="EmaServ">     
      </div>
    </div>

    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon"> Puerto </span>
          <input class="form-control input-sm" type="text" value="{{ $parametros['EmaPuer'] }}" name="EmaPuer">     
      </div>
    </div>

    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon see_password"> Clave </span>
          <input class="form-control input-sm" type="text" class="password_change" value="{{ $parametros['EmaClav'] }}" name="EmaClav">     
      </div>
    </div>

    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon"> Encriptacion </span>
          <input class="form-control input-sm" type="text" value="{{ $parametros['zoncodi'] }}" name="zoncodi">     
      </div>
    </div>

  </div>

  @foreach( $parametros as $name => $value )
    @php
      $info_settings = $settings->getInfoSetting( $name );
      // dump( "$name - $value");
    @endphp
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
</div>