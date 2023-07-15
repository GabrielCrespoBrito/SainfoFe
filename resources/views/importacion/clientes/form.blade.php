<div class="div-producto">

  <form method="post" data-isventa="{{ (string) isset($venta) }}" action="{{ route('importar.productos.store') }}" id="form-accion" enctype="multipart/form-data">

    @csrf


    {{-- --}}
    <div class="row">
      <div class="checkbox col-md-12">

        <label>
          <input name="grupos" value="grupos" {{ !old('grupos') ? '' : 'checked=checked'  }} type="checkbox"> Grupo
        </label>

        <label>
          <input name="marcas" value="marcas" {{ !old('marca') ? '' : 'checked=checked'  }} type="checkbox"> Marca
        </label>

        <label>
          <input name="familias" value="familias" {{ !old('familias') ? '' : 'checked=checked'  }} type="checkbox"> Familia
        </label>

        <label>
          <input name="productos" value="productos" {{ !old('productos') ? '' : 'checked=checked'  }} type="checkbox"> Producto
        </label>

        <label>
          <input name="prov_clientes" value="clientess" {{ !old('clientess') ? '' : 'checked=checked'  }} type="checkbox"> Clientes
        </label>

      </div>
    </div>


    <div class="row">
      <div class="col-md-12 {{ $errors->has('excel') ? 'has-error' : '' }}">

        <div class="input-file-container">
          <input class="input-file" id="my-file" name="excel" type="file">
          <label tabindex="0" for="my-file" class="input-file-trigger"> <span class="fa fa-upload"></span> Seleccionar archivo..</label>
          <p class="file-return"></p>
        </div>


        @if ($errors->has('excel'))
        <span class="invalid-feedback"> <strong>{{ $errors->first('excel') }}</strong> </span>
        @endif

      </div>
    </div>


    {{-- Botones --}}
    <div class="row">
      <div class="col-xs-12">
        <button type="submit" class="btn btn-primary btn-flat send_info"><span class="fa fa-save"></span> Guardar</button>
        <a href="{{ config('app.excel_productos_template') }}" class="btn pull-right btn-default btn-flat"><span class="fa fa-save"></span> Excel de Ejemplo</a>
      </div>
    </div>

  </form>

</div>