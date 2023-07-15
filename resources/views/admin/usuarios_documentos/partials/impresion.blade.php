<div class="row">

{{-- Titulo --}}
  <div class="col-md-12">
    <h4 class="text-center usuario-documento-titulo"> Impresiòn directa </h4>
  </div>
{{-- Titulo --}}


{{--  --}}
  <div class="form-group {{ $errors->first('impresion_directa == "1') ? 'has-error' : '' }}  col-md-4">
    <div class="parent-test-print-btn  {{  $usuario_documento->impresion_directa == "1" ? 'input-group' : '' }}">

    <label> Impresiòn Directa (*)</label>
    <select class="form-control" name="impresion_directa">
      <option value="0" {{ $usuario_documento->impresion_directa == "0" ? 'selected=selected' : '' }}> Inactivo </option>
      <option value="1" {{ $usuario_documento->impresion_directa == "1" ? 'selected=selected' : '' }}> Activo </option>
    </select>
    <span style="padding: 0;border: 0;margin: 0;vertical-align: bottom;" class="spanBtnBtnTest input-group-addon {{ $usuario_documento->impresion_directa == "1" ? '' : 'hide' }}"> <a href="#" data-route="{{ route('usuario_documento.test_print') }}"  class="btn-md btn btn-flat btn-default print-test"> <span class="fa fa-print"></span> Probar impresiòn </a> </span>
    </div>
  </div>
{{-- --}}


{{-- --}}
  <div class="form-group {{$errors->first('cantidad_copias') ? 'has-error' : '' }}  col-md-4">
    <label> Cantidad de Copias (*)</label>
    <input type="number" min="0" class="form-control" name="cantidad_copias" value="{{  $usuario_documento->cantidad_copias }}">

    @if( $errors->has('cantidad_copias') )
    <span class="help-block">{{ $errors->first('cantidad_copias') }}</span>
    @endif
  </div>
{{-- --}}


{{-- --}}
  <div class="form-group {{$errors->first('nombre_impresora') ? 'has-error' : '' }}  col-md-4">
    <label> Nombre de la Impresora (*) </label>
    <input type="text" class="form-control" name="nombre_impresora" value="{{ $usuario_documento->nombre_impresora }}">
    @if( $errors->has('nombre_impresora') )
    <span class="help-block">{{ $errors->first('nombre_impresora') }}</span>
    @endif
  </div>
{{-- --}}


</div>
