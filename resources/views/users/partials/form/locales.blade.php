<hr>

<p>  <span class="fa fa-building"></span> Local </p>

@foreach( $locales as $local )

@php
  $isChecked = false;
  if( $create == false ){
    $isChecked = $user_locales->where('loccodi' , $local->LocCodi)->count();
  }

@endphp

<div class="row">
<div class="form-group col-md-12">
<label> <input name="local[]" {{ $isChecked ? 'checked' : '' }} value="{{ $local->LocCodi }}" type="checkbox"> {{ $local->LocNomb }} <span style="font-weight: normal;    color: gray;font-style: italic;">{{ $local->LocDire }}</span> </label>
</div>
</div>

@endforeach
