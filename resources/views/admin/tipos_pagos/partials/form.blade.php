<div class="row">

  <div class="form-group {{$errors->first('TpgCodi') ? 'has-error' : '' }}  col-md-3">
    <label> Codigo </label>
    <input disabled type="text" class="form-control" name="TpgCodi" value="{{  old( 'TpgCodi' , $model->TpgCodi )  }}">
    @if( $errors->has('TpgCodi') )
    <span class="help-block">{{ $errors->first('TpgCodi') }}</span>
    @endif
  </div>

  <div class="form-group {{$errors->first('TpgNomb') ? 'has-error' : '' }}  col-md-6">
    <label> Nombre *</label>
    <input required  type="text" class="form-control text-uppercase" name="TpgNomb" value="{{  old( 'TpgNomb' , $model->TpgNomb )  }}">
    @if( $errors->has('TpgNomb') )
    <span class="help-block">{{ $errors->first('TpgNomb') }}</span>
    @endif
  </div>

  <div class="form-group {{ $errors->first('TdoBanc') ? 'has-error' : '' }}  col-md-3">
    <label> Bancario *</label>
    <select class="form-control" name="TdoBanc">
      <option value="1" {{ $model->TdoBanc === "1" ? 'selected=selected' : '' }}> Si </option>
      <option value="0" {{ $model->TdoBanc === "0" ? 'selected=selected' : '' }}> No </option>
    </select>
    @if( $errors->has('TdoBanc') )
    <span class="help-block">{{ $errors->first('TdoBanc') }}</span>
    @endif
  </div>

</div>