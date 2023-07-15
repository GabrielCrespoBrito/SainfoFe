<form action="{{ route('admin.config.store') }}" method="post">
@csrf
<div class="row">
  @foreach( $configuraciones as $configuracion )
    <div class="form-group col-md-6">
      <label for="Config{{ $configuracion->id }}">{{ $configuracion->name }}
        <span class="fa fa-commenting-o"></span>  </label>
        @php 
          $isSelect = $configuracion->isSelect();
        @endphp
        @if( $isSelect )
          <select name="{{ $configuracion->id }}" class="form-control"> 
          @foreach( $isSelect['values'] as $name => $value )
            <option {{ $value == $configuracion->value ? 'selected=selected' : '' }} value="{{ $value }}"> {{ $name }}</option>
          @endforeach
          </select>
        @else
          <input type="text" class="form-control" name="{{ $configuracion->id }}" id="Config{{ $configuracion->id }}" value="{{ $configuracion->value }}">     
        @endif

    </div>
  @endforeach
</div>  

<div class="row">
  <div class="col-md-12">
    <button type="submit" class="btn btn-primary">  <span class="fa fa-save"></span> Guardar
    </button>
    <a href="{{ route('home') }}" class="btn btn-danger"> Salir </a>

  </div>
</div>
</form>