<!-- Almacen -->
<div class="reportes">
<div class="filtro">
  <div class="cold-md-12">
    <fieldset class="fsStyle">
      <legend class="legendStyle">Almacen</legend>
      <div class="row" id="demo">
        
        <div class="col-md-8" style="padding-left:20px">
          <select type="text" requred name="local_stock" class="form-control input-sm flat text-center">
            <option value="todos">---- TODOS ----</option>
            @foreach( $locales as $local )
            <option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <div class="checkbox pt-0 mt-0">
            <label>
              <a href="#" data-action="{{ route('productos.update_stock') }}" class="btn btn-flat btn-primary btn-block update-stock btn-sm"> Actualizar Stock de Almacen  </a>
            </label>
          </div>
        </div>    

      </div>
    </fieldset>
  </div>
</div>
</div>
<!-- /Almacen -->