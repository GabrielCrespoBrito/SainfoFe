<div class="row">

  <div class="form-group {{$errors->first('tidcodi') ? 'has-error' : '' }}  col-md-4">
    <label> Tipo de documento *</label>
    <select class="form-control" name="tidcodi">
      @foreach( $tipo_documentos as $tipo_documento )
      <option 
      data-tipo="{{ $tipo_documento->getNombreForPlantilla() }}"
      {{ $usuario_documento->tidcodi == $tipo_documento->TidCodi ? 'selected=selected' : '' }} value="{{ $tipo_documento->TidCodi }}">{{ $tipo_documento->TidNomb }}</option>
      @endforeach
    </select>
    @if( $errors->has('tidcodi') )
    <span class="help-block">{{ $errors->first('tidcodi') }}</span>
    @endif
  </div>

  <div class="form-group {{$errors->first('sercodi') ? 'has-error' : '' }}  col-md-4">
    <label> Serie *</label>
    <input type="text" value="{{ old('sercodi', $usuario_documento->sercodi)   }}" class="form-control text-uppercase" name="sercodi">
    @if( $errors->has('sercodi') )
    <span class="help-block">{{ $errors->first('sercodi') }}</span>
    @endif
  </div>

  <div class="form-group {{$errors->first('numcodi') ? 'has-error' : '' }}  col-md-4">
    <label> Número *</label>
    <input type="text" class="form-control" name="numcodi" value="{{  old( 'numcodi' , $usuario_documento->numcodi )  }}">
    @if( $errors->has('numcodi') )
    <span class="help-block">{{ $errors->first('numcodi') }}</span>
    @endif
  </div>

</div>

