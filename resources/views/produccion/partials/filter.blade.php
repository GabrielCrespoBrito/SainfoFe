<div class="col-md-8 ventas">
  <div class="row">
    <div class="col-md-6 no_p">
      @include('components.btn_procesando')      
      @include('components.specific.select_mes')
    </div>
    <div class="col-md-3">
      <select name="local" data-reloadtable="table" class="form-control input-sm">        
        @foreach ($locales as $local)
          <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}> {{ optional($local->local)->LocNomb }} </option>
        @endforeach
      </select>
    </div>
    @include('components.specific.select_estado_almacen', ['size' => 'col-md-3' ])
  </div>
</div>
<div class="col-md-4">
  <a href="{{ route('compras.create') }}" class="btn btn-primary pull-right  btn-flat"> Crear </a>  
</div>