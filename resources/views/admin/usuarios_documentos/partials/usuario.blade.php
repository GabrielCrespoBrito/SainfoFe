@include('admin.partials.empresa_input')

  {{-- @dd( $users->id() ) --}}
  <div class="row">

    <div class="form-group {{ $errors->first('usucodi') ? 'has-error' : '' }}  col-md-6">
      <label> Usuario *</label>
      <select class="form-control" {{ $id_user == 'all' ? '' : 'disabled=disabled' }} name="usucodi">
         
        @if( $id_user == "all" )
          @foreach( $users as $user )
          <option {{ $user->usucodi == session('usucodi') ? 'selected' : '' }}   value="{{ $user->id() }}"> {{ $user->usucodi }} - {{ $user->usulogi }} </option>
          @endforeach

        @else

        <option value="{{ $users->usucodi }}"> {{ $users->usucodi }} - {{ $users->usulogi }}</option>

        @endif

      </select>
      @if( $errors->has('usucodi') )
      <span class="help-block">{{ $errors->first('id_user') }}</span>
      @endif
    </div>

    <div class="form-group {{ $errors->first('loccodi') ? 'has-error' : '' }}  col-md-6">
      <label> Local *</label>
      <select class="form-control" name="loccodi">
        @foreach( $locales as $local )
        <option {{  $usuario_documento->loccodi  == $local->LocCodi ? 'selected=selected' : '' }} value="{{ $local->LocCodi }}"> {{ $local->LocCodi }} - {{ $local->LocNomb }}</option>
        @endforeach
      </select>
      @if( $errors->has('loccodi') )
      <span class="help-block">{{ $errors->first('id_local') }}</span>
      @endif
    </div>

  </div>