<div class="form-group">
  <select name="status" class="form-control input-sm">
    <option value=""> -- TODOS -- </option>
    @foreach( $codes as $code )
      <option value="{{ $code->id }}"> {{ $code->tipo . ' | ' .  $code->status_code . ' | ' . $code->status_message }} </option>
    @endforeach
  </select>
</div>  
