  @php
  $logos_dimenciones = config('app.logos_dimenciones');
  @endphp

{{-- Logos Secunarios --}}

<div class="row">

  {{-- Logo marca agua --}}
  <div class="form-group {{ $errors->has('logo_marca_agua') ? 'has-error' : '' }} col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Imagen Marca de Agua </span>
      <input class="form-control input-sm" name="logo_marca_agua" type="file" value="">
    </div>

    <div class="text-right">
      <span class="imagen-dimenciones">Dimenciones maximas de la imagen es {{ $logos_dimenciones['marca_agua']['width'] . ' x ' . $logos_dimenciones['marca_agua']['height'] }} </span>
    </div>

    {{-- @dd( $empresa->hasLogoMarcaAgua() ) --}}

    @if($empresa->hasLogoMarcaAgua())
    <div class="form-group imagen_div">
      <img class="img_empresa img_logo_secundario" src="data:image/png;base64,{{ $empresa->getLogoEncodeMarcaAgua() }}">
    </div>

    <div class="form-group imagen_remove">
      <a data-form_action="{{ route('empresa.deleteLogo', [ 'id_empresa' => $empresa->id() , 'logo' => 4 ] ) }}" href="#" class="btn btn-xs btn-danger delete_logo"> Quitar imagen </a>
    </div>

    @endif

  </div>
  {{-- /Logo marca agua --}}

  {{-- Logo SUBTITULO --}}
  <div class="form-group {{ $errors->has('logo_subtitulo') ? 'has-error' : '' }} col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Imagen Subtitulo </span>
      <input class="form-control input-sm" name="logo_subtitulo" type="file" value="">
    </div>

    <div class="text-right">
      <span class="imagen-dimenciones">Dimenciones maximas de la imagen es {{ $logos_dimenciones['subtitulo']['width'] . ' x ' . $logos_dimenciones['subtitulo']['height'] }} </span>
    </div>

    @if($empresa->hasLogoSubtitulo())
    <div class="form-group imagen_div">
      <img class="img_empresa img_logo_secundario" src="data:image/png;base64,{{ $empresa->getLogoEncodeSubtitulo() }}">
    </div>

    <div class="form-group imagen_remove">
      <a data-form_action="{{ route('empresa.deleteLogo', [ 'id_empresa' => $empresa->id() , 'logo' => 3 ] ) }}" href="#" class="btn btn-xs btn-danger delete_logo"> Quitar imagen </a>
    </div>
    
    @endif

  </div>
  {{-- /Logo principal --}}

</div>

{{-- Logos Secunarios --}}
