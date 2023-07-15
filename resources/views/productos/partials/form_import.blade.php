<div class="div-producto">

  <form method="post" data-isventa="{{ (string) isset($venta) }}" action="{{ route('productos.import_store') }}" id="form-accion" enctype="multipart/form-data" >

    @csrf

    <div class="row">
      @if( ! isset($venta) )
      <div class="checkbox col-md-12">
        <label>
          <input name="grupos" value="grupos" {{ !old('grupos') ? '' : 'checked=checked'  }} type="checkbox"> Grupo
        </label>
        <label>
          <input name="marcas" value="marcas" {{ !old('marca') ? '' : 'checked=checked'  }}  type="checkbox"> Marca
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
      @endif
    </div>  

      <div class="row">
        <div class="checkbox col-md-12 {{ $errors->has('excel') ? 'has-error' : '' }}" >
        <label>
          <input name="excel" type="file"> 
        </label>
        @if ($errors->has('excel'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('excel') }}</strong>
            </span>
        @endif

      </div> 
    </div>

  <div class="row">
    <div class="col-xs-12">
      
      <button  type="submit" class="btn btn-primary btn-flat send_info"><span class="fa fa-save"></span> Guardar</button>

      @php 
        $excel_tipo = isset($venta) ? "ventas" : "productos";
      @endphp
      <a target="_blank" href="{{ route('productos.excel_ejemplo', $excel_tipo ) }}" class="btn pull-right btn-default btn-flat"><span class="fa fa-save"></span> Excel de Ejemplo</a>


    </div>
  </div>
  </form>

</div> 