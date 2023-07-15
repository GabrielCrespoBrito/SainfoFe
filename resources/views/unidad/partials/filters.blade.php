  <div class="row contenido">

    <div class="col-md-3">
      <label> Local  </label>
      <select class="form-control"  name="local_id">
        @foreach( $locales as $local )
          <option data-listas="{{ json_encode($local->local->listas) }}" {{ $local_current == $local->loccodi ? 'selected' : '' }}  value="{{ $local->loccodi }}"> {{ $local->local->LocNomb }} </option>
        @endforeach
      </select>
    </div>

    {{-- <div class="col-md-3">
      <label> Listas de Precios  </label>
      <select class="form-control" name="LisCodi">
        <option value=""> -- TODOS -- </option>
        @foreach( $locales as $local )
        <optgroup label="LOCAL: {{ $local->local->LocNomb }}">
          @foreach( $local->local->listas as $lista )
          <option data-loccodi="{{ $local->loccodi }}" value="{{ $lista->LisCodi }}">{{ $lista->LisNomb }}</option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
    </div> --}}

    <div class="col-md-3">
      <label> Listas de Precios  </label>
      <select class="form-control" name="LisCodi">
        <option value=""> -- TODOS -- </option>
        @foreach( $listas as $lista )
          <option {{ $loop->first ? 'selected' : '' }} data-loccodi="{{ $local->loccodi }}" value="{{ $lista->LisCodi }}">{{ $lista->LisNomb }}</option>
        @endforeach
      </select>
    </div>


    <div class="col-md-2">
      <label> Grupos  </label>      
      <select class="form-control"
        data-url="{{ route('productos.buscar_grupo') }}"
        name="grupo_id">
        <option value=""> -- TODOS -- </option>
        @foreach( $grupos as $grupo )
        <option data-familias="" value="{{ $grupo->id }}">{{ $grupo->descripcion }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <label> Familias  </label>      
      <select class="form-control" name="familia_id">
        <option value="">-- TODOS --</option>
      </select>
    </div>

    <div class="col-md-2">
      <label> Marcas  </label>      
      <select class="form-control" name="marca_id">
        <option value=""> -- TODOS -- </option>
        @foreach( $marcas as $marca )
        <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
        @endforeach
      </select>
    </div>

  </div>
