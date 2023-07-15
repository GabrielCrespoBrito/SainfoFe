{{-- Logos --}}

<div class="row">
  @php
  $logos_dimenciones = config('app.logos_dimenciones');
  @endphp

  {{-- Logo principal --}}
  <div class="form-group {{ $errors->has('logo_principal') ? 'has-error' : '' }} col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Imagen para impresiòn <span class="resaltar-formato">A4/A5</span> </span>
      <input class="form-control input-sm" name="logo_principal" type="file" value="">
    </div>

    <div class="text-right">
      <span class="imagen-dimenciones">Dimenciones maximas de la imagen es {{ $logos_dimenciones['a4']['width'] . ' x ' . $logos_dimenciones['a4']['height'] }} </span>
    </div>

    @if($empresa->hasLogo())

    <div class="form-group imagen_div">
      <img class="img_empresa img_logo_secundario" src="data:image/png;base64,{{ $empresa->logoEncode() }}">
    </div>
    @endif

  </div>
  {{-- /Logo principal --}}

  {{-- Logo Ticket --}}
  <div class="form-group {{ $errors->has('logo_secundario') ? 'has-error' : '' }} col-md-6">

    <div class="input-group">
      <span class="input-group-addon">Imagen para impresiòn <span class="resaltar-formato">Ticket</span></span>
      <input class="form-control input-sm" name="logo_secundario" type="file" value="">
    </div>


    <div class="text-right">
      <span class="imagen-dimenciones">Dimenciones maximas de la imagen es {{ $logos_dimenciones['ticket']['width'] . ' x ' . $logos_dimenciones['ticket']['height'] }} </span>
    </div>

    @if($empresa->hasLogo(2))
    <div class="form-group imagen_div">
      <img class="img_empresa img_logo_secundario" src="data:image/png;base64,{{ $empresa->logoEncode(2) }}">
    </div>

    <div class="form-group imagen_remove">
      <a data-form_action="{{ route('empresa.deleteLogo', [ 'id_empresa' => $empresa->id() , 'logo' => 2 ] ) }}" href="#" class="btn btn-xs btn-danger delete_logo"> Quitar imagen </a>
    </div>
    @endif

  </div>
  {{-- /Logo Ticket --}}

</div>
