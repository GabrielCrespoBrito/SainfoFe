<div class="div-producto">

  {{-- <form method="post" action="{{ route('importar.productos.store') }}" id="form-accion" enctype="multipart/form-data"> --}}
  <form method="post" action="{{  $route }}" id="form-accion" enctype="multipart/form-data">

    @csrf

    <div class="row">
      <div class="col-md-12 {{ $errors->has('excel') ? 'has-error' : '' }}">

        <div class="input-file-container">
          <input id="inputFile" class="input-file" id="my-file" name="excel" type="file">
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