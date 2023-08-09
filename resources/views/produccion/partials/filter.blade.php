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

    <a href="{{ route('produccion.create') }}" class="btn btn-flat btn-primary pull-right"> <span class="fa fa-plus"></span> Nuevo </a>
    
    <a href="{{ route('produccion.pdf') }}" style="margin-right: 5px" class="btn btn-flat btn-default pull-right"> <span class="fa fa-file-pdf-o"></span>  Reporte </a>

  </div>
</div>