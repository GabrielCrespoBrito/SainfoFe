<div class="row">
  <div class="col-md-6">
    <select name="local" data-reloadtable="table" class="form-control input-sm">
      <option value=""> - TODOS - <option>
      @foreach($estados as $id => $nombre)
      <option value="{{ $id }}"> {{ $nombre }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">

    <a href="{{ route('produccion.create') }}" class="btn btn-flat btn-primary pull-right"> Nuevo </a>

  </div>
</div>